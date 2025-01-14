<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//model
use App\Models\ClassAssignment;
use App\Models\ClassSubmit;
use App\Models\Assignment;
use App\Models\AssignmentQuiz;
use App\Models\Choice;
use App\Models\ClassAnswer;
use App\Models\Question;
use App\Models\QuizPackage;

class AssignmentController extends Controller
{
    public function GetAssignmentByClassId($class_id, $role)
    {
        $homeworks = null;
        if ($role == 2) {
            $homeworks = ClassAssignment::where('class_id', $class_id)->get();
        } elseif ($role == 3) {
            $homeworks = ClassAssignment::where('class_id', $class_id)->where('status', 'published')->get();
        }
        $assignments = $homeworks->map(function ($homework) {
            $answers = ClassSubmit::where('class_assignment_id', $homework->id)->where('student_id', Auth::user()->id)->get();
            return [
                'id' => $homework->id,
                'type' => $homework->type,
                'title' => $homework->title,
                'description' => $homework->description,
                'duration' => $homework->duration ? $homework->duration : "Không có",
                'startDate' => $homework->start_date,
                'dueDate' => $homework->due_date,
                'status' => $homework->status,
                'isSubmitted' => $answers->count() >= 1 ? true : false,
            ];
        });

        return response()->json([
            'assignments' => $assignments,
        ], Response::HTTP_OK);
    }

    public function CreateAssignment(Request $request)
    {
        $classAssignment = ClassAssignment::create([
            'class_id' => $request->class_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
            'start_date' => $request->start_datetime,
            'due_date' => $request->due_datetime,
            'status' => $request->status,
        ]);

        if ($request->type == 'lab') {
            return response()->json([
                'message' => 'Tạo bài tập thành công',
                'data' => $request->all(),
            ], Response::HTTP_CREATED);
        }

        $package = QuizPackage::find($request->quiz_package_id);
        $numberOfQuestions = $request->number_of_questions;

        for ($i = 0; $i < $numberOfQuestions; $i++) {
            $quiz = $package->quizzes->random();
            AssignmentQuiz::create([
                'assignment_id' => $classAssignment->id,
                'quiz_id' => $quiz->id,
            ]);
        }

        return response()->json([
            'message' => 'Tạo đề thi thành công',
            'data' => $request->all(),
        ], Response::HTTP_CREATED);
    }

    public function UpdateAssignment(Request $request)
    {
        $homework = ClassAssignment::find($request->id);
        if (!$homework) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy đề thi.',
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        $homework->title = $request->title;
        $homework->description = $request->description;
        if ($homework->type == "quiz") $homework->duration = $request->duration;
        $homework->start_date = $request->start_datetime;
        $homework->due_date = $request->due_datetime;
        $homework->status = $request->status;
        $homework->save();

        return response()->json([
            'message' => 'Cập nhật đề thi thành công',
        ], Response::HTTP_OK);
    }

    public function UpdateAssignmentQuizzes(Request $request)
    {
        $classAssignmentId = $request->class_assignment_id;
        $quizPackageId = $request->quiz_package_id;
        $numberOfQuestions = $request->number_of_questions;

        ClassAssignment::find($classAssignmentId)->quizzes()->delete();

        $package = QuizPackage::find($quizPackageId);
        $selectedQuizIds = [];

        for ($i = 0; $i < $numberOfQuestions; $i++) {
            do {
                $quiz = $package->quizzes->random();
            } while (in_array($quiz->id, $selectedQuizIds));

            $selectedQuizIds[] = $quiz->id;

            AssignmentQuiz::create([
                'assignment_id' => $classAssignmentId,
                'quiz_id' => $quiz->id,
            ]);
        }

        return response()->json([
            'message' => 'Cập nhật đề thi thành công',
        ], Response::HTTP_OK);
    }

    public function SubmitAssignment(Request $request)
    {
        $user = Auth::user();
        if ($request->type == 'lab') {
            ClassSubmit::create([
                'class_assignment_id' => $request->assignment_id,
                'student_id' => $user->id,
                'answer' => $request->link,
                'score' => 0,
            ]);

            return response()->json([
                'message' => 'Nộp bài thành công',
            ], Response::HTTP_OK);
        } else {
            $answers = json_decode($request->answers, true);
            $totalScore = 0;

            $submit = ClassSubmit::create([
                'class_assignment_id' => $request->assignment_id,
                'student_id' => $user->id,
            ]);

            foreach ($answers as $answer) {
                ClassAnswer::create([
                    'class_submit_id' => $submit->id,
                    'quiz_id' => $answer['question_id'],
                    'choice_id' => $answer['choice_id'],
                ]);

                $choice = Choice::find($answer['choice_id']);

                if ($choice && $choice->is_correct == 1) {
                    $totalScore += 1;
                }
            }

            $submit->score = $totalScore;
            $submit->save();

            return response()->json([
                'message' => 'Nộp bài thành công',
                'score' => $totalScore
            ], Response::HTTP_OK);
        }
    }

    public function getAssignmentById($id, $isTeacher)
    {
        $assignment = ClassAssignment::where('id', $id)->with('quizzes.choices')->first();

        if (!$assignment) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy đề thi.',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $response = [
            'id' => $assignment->id,
            'type' => $assignment->type,
            'title' => $assignment->title,
            'description' => $assignment->description,
            'duration' => $assignment->duration ? $assignment->duration : "Không có",
            'startDate' => $assignment->start_date,
            'dueDate' => $assignment->due_date,
            'status' => $assignment->status,
            'questions' => $assignment->quizzes->map(function ($quiz) use ($isTeacher) {
                return [
                    'id' => $quiz->id,
                    'question' => $quiz->question,
                    "choices" => $quiz->choices->map(function ($choice) use ($isTeacher) {
                        return [
                            'id' => $choice->id,
                            'choice' => $choice->choice,
                            'isCorrect' => $isTeacher == 1 ? $choice->is_correct : null,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json([
            'assignment' => $response,
        ], Response::HTTP_OK);
    }
}
