<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//model
use App\Models\Homework;
use App\Models\Assignment;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Classes;
use App\Models\Submission;

class AssignmentController extends Controller
{
    public function GetAssignmentByClassId($class_id, $role)
    {
        $homeworks = null;
        if ($role == 1) {
            $homeworks = Homework::where('class_id', $class_id)->with('assignment')->get();
        } else {
            $homeworks = Homework::where('class_id', $class_id)->where('status', 1)->with('assignment')->get();
        }
        $assignments = $homeworks->map(function ($homework) {
            $answers = Answer::where('user_id', Auth::user()->id)->where('assignment_id', $homework->assignment ? $homework->assignment->id : $homework->id)->get();
            return [
                'id' => $homework->assignment ? $homework->assignment->id : $homework->id,
                'homeworkId' => $homework->id,
                'creatorName' => $homework->assignment ? $homework->assignment->creator->name : null,
                'name' => $homework->assignment ? $homework->assignment->title : $homework->title,
                'description' => $homework->assignment ? $homework->assignment->description : $homework->description,
                'level' => $homework->assignment ? $homework->assignment->level : "Không có",
                'totalScore' => $homework->assignment ? $homework->assignment->totalScore : $homework->score,
                'specialized' => $homework->assignment ? $homework->assignment->specialized : "Không có",
                'subject' => $homework->assignment ?  $homework->assignment->subject : "Không có",
                'topic' => $homework->assignment ? $homework->assignment->topic : "Không có",
                'type' => $homework->type,
                'isSubmitted' => $answers->count() > 1 ? true : false,
            ];
        });

        return response()->json([
            'assignments' => $assignments,
        ], Response::HTTP_OK);
    }

    public function GetAssignmentForTeacher()
    {
        $user = Auth::user();
        $assignments = Assignment::where('status', 'published')
            ->orWhere(function ($query) use ($user) {
                $query->where('status', 'private')
                    ->where('creator_id', $user->id);
            })
            ->get();

        $assignmentsFormatted = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'name' => $assignment->title,
                'description' => $assignment->description,
                'level' => $assignment->level,
                'totalScore' => $assignment->totalScore,
                'specialized' => $assignment->specialized,
                'subject' => $assignment->subject,
                'topic' => $assignment->topic,
                'creator' => $assignment->creator->name,
                'isCreator' => $assignment->creator_id == Auth::user()->id ? true : false,
                'createdAt' => $assignment->created_at->format('H:i d:m:Y'),
                'numberOfQuestions' => $assignment->questions->count(),
            ];
        });

        return response()->json([
            'assignments' => $assignmentsFormatted,
        ], Response::HTTP_OK);
    }

    public function CreateAssignment(Request $request)
    {
        $rules = [
            'create_homework' => 'required|String',
            //assignment
            'assignment_id' => 'String',
            'title' => 'string',
            'description' => 'string',
            'status' => 'in:closed,published,private,draft',
            'level' => 'string',
            'totalScore' => 'integer',
            'specialized' => 'string',
            'subject' => 'string',
            'topic' => 'string',
            //question
            // 'questions' => 'json',
        ];

        if ($request->create_homework == 'true') {
            $rules['class_id'] = 'required|integer';
            $rules['type'] = 'required|in:link,quiz';
            $rules['title'] = 'required|string';
            $request->type != "link" ?? $rules['score'] = 'required|integer';
            $rules['start_datetime'] = 'required|string';
            $rules['due_datetime'] = 'required|string';
            $rules['duration'] = 'required|integer';
            $request->type != "link" ?? $rules['auto_grade'] = 'required|string';
            $rules['homework_status'] = 'required|integer';
        }

        $messages = [
            'create_homework.required' => 'only_assignment không được để trống',
            'create_homework.string' => 'only_assignment phải là chuỗi',
            'assignment_id.string' => 'assignment_id phải là chuỗi',
            'title.string' => 'title phải là chuỗi',
            'description.string' => 'description phải là chuỗi',
            'status.in' => 'status phải là closed, published, private hoặc draft',
            'level.string' => 'level phải là chuỗi',
            'totalScore.integer' => 'totalScore phải là số nguyên',
            'specialized.string' => 'specialized phải là chuỗi',
            'subject.string' => 'subject phải là chuỗi',
            'topic.string' => 'topic phải là chuỗi',
            'questions.json' => 'questions phải là json',
            'class_id.required' => 'class_id không được để trống',
            'class_id.integer' => 'class_id phải là số nguyên',
            'type.required' => 'type không được để trống',
            'type.in' => 'type phải là link hoặc quiz',
            'title.string' => 'title phải là chuỗi',
            'score.integer' => 'score phải là số nguyên',
            'start_datetime.required' => 'start_datetime không được để trống',
            'start_datetime.string' => 'start_datetime phải là chuỗi',
            'due_datetime.required' => 'due_datetime không được để trống',
            'due_datetime.string' => 'due_datetime phải là chuỗi',
            'duration.required' => 'duration không được để trống',
            'duration.integer' => 'duration phải là số nguyên',
            'auto_grade.string' => 'auto_grade phải là boolean',
            'homework_status.required' => 'status không được để trống',
            'homework_status.integer' => 'status phải là số nguyên',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);
        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }
        $user = Auth::user();
        $assignment = $request->type == 'quiz' ? new Assignment() : null;
        if ($request->type == 'quiz' && $request->assignment_id == null) {
            $assignment->creator_id = $user->id;
            $assignment->title = $request->title;
            $assignment->description = $request->description;
            $assignment->status = $request->status;
            $assignment->level = $request->level;
            $assignment->totalScore = $request->totalScore;
            $assignment->specialized = $request->specialized;
            $assignment->subject = $request->subject;
            $assignment->topic = $request->topic;
            $assignment->save();

            //convert question to array
            $questions = json_decode($request->questions, true);

            foreach ($questions as $questionData) {
                $question = Question::create([
                    'assignment_id' => $assignment->id,
                    'question' => $questionData['question'],
                    'duration' => "00:00:00",
                    'score' => $questionData['score'],
                ]);

                foreach ($questionData['choices'] as $choiceData) {
                    Choice::create([
                        'question_id' => $question->id,
                        'choice' => $choiceData['choice'],
                        'is_correct' => $choiceData['is_correct'],
                    ]);
                }
            }
        }

        if ($request->create_homework == 'true') {
            $assignmentId = null;
            if ($request->type == 'quiz' && $request->assignment_id == null) {
                $assignmentId = $assignment->id;
            } else if ($request->type == 'link') {
                $assignmentId = null;
            } else {
                $assignmentId = $request->assignment_id;
            }
            $homework = new Homework();
            $homework->class_id = $request->class_id;
            $homework->assignment_id = $assignmentId;
            $homework->type = $request->type;
            $homework->title = $request->type == 'link' ? $request->title : null;
            $homework->score = $request->type == 'link' ? $request->score : null;
            $homework->description = $request->type == 'link' ? $request->description : null;
            $homework->start_datetime = $request->start_datetime;
            $homework->due_datetime = $request->due_datetime;
            $homework->duration = $request->duration;
            if ($request->type != 'link') {
                $homework->auto_grade = $request->auto_grade == 'true' ? true : false;
            } else {
                $homework->auto_grade = false;
            }
            $homework->status = $request->homework_status;
            $homework->save();
        }

        return response()->json([
            'message' => 'Tạo đề thi thành công',
        ], Response::HTTP_CREATED);
    }

    public function UpdateAssignment(Request $request)
    {
        $homework = Homework::find($request->id);
        if (!$homework) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy đề thi.',
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        $homework->start_datetime = $request->start_datetime;
        $homework->due_datetime = $request->due_datetime;
        $homework->duration = $request->duration;
        $homework->status = $request->status;
        if ($request->type == "quiz") {
            $homework->assignment_id = $request->assignment_id;
            $homework->auto_grade = $request->auto_grade;
        } else {
            $homework->title = $request->title;
            $homework->score = $request->score;
            $homework->description = $request->description;
        }
        $homework->save();

        return response()->json([
            'message' => 'Cập nhật đề thi thành công',
        ], Response::HTTP_OK);
    }

    public function SubmitAssignment(Request $request)
    {
        $user = Auth::user();
        if ($request->type == 'link') {
            Answer::create([
                'user_id' => $user->id,
                'homework_id' => $request->assignment_id,
                'link' => $request->link,
            ]);

            Submission::create([
                'student_id' => $user->id,
                'total_score' => 0,
                'class_id' => $request->class_id,
            ]);

            return response()->json([
                'message' => 'Nộp bài thành công',
            ], Response::HTTP_OK);
        } else {
            $answers = json_decode($request->answers, true);
            $totalScore = 0;

            foreach ($answers as $answer) {
                Answer::create([
                    'user_id' => $user->id,
                    'assignment_id' => $request->assignment_id,
                    'question_id' => $answer['question_id'],
                    'choice_id' => $answer['choice_id'],
                ]);

                $question = Question::find($answer['question_id']);
                $choice = Choice::find($answer['choice_id']);

                if ($choice && $choice->is_correct == 1) {
                    $totalScore += $question->score;
                }
            }

            Submission::create([
                'assignment_id' => $request->assignment_id,
                'student_id' => $user->id,
                'total_score' => $totalScore,
                'class_id' => $request->class_id,
            ]);

            return response()->json([
                'message' => 'Nộp bài thành công',
                'score' => $totalScore
            ], Response::HTTP_OK);
        }
    }

    public function getAssignment($id, $class_id)
    {
        $class = Classes::find($class_id)::with('homeworks', 'homeworks.assignment', 'homeworks.assignment.questions', 'homeworks.assignment.questions.choices')->first();
        $assignment = $class->homeworks->where('assignment_id', $id)->first();

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
            'creatorName' => $assignment->assignment->creator ? $assignment->assignment->creator->name : null,
            'name' => $assignment->assignment->title,
            'description' => $assignment->assignment->description,
            'duration' => $assignment->duration,
            'startDate' => $assignment->start_datetime,
            'dueDate' => $assignment->due_datetime,
            'questions' => $assignment->assignment->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'duration' => $question->duration,
                    'score' => $question->score,
                    "choices" => $question->choices->map(function ($choice) {
                        return [
                            'id' => $choice->id,
                            'choice' => $choice->choice,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json([
            'assignment' => $response,
        ], Response::HTTP_OK);
    }

    public function getHomeworkByIdToEdit($id)
    {
        $homework = Homework::find($id);
        if (!$homework) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy đề thi.',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $homeworkFormatted = [
            'title' => $homework->type == 'link' ? $homework->title : null,
            'score' => $homework->type == 'link' ? $homework->score : null,
            'description' => $homework->type == 'link' ? $homework->description : null,
            'duration' => $homework->duration,
            'autoGrade' => $homework->auto_grade,
            'startDate' => $homework->start_datetime,
            'dueDate' => $homework->due_datetime,
            'status' => $homework->status,
            'assignmentId' => $homework->assignment_id,
        ];

        return response()->json([
            'homework' => $homeworkFormatted,
        ], Response::HTTP_OK);
    }
}
