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

class AssignmentController extends Controller
{
    public function GetAssignmentByClassId($class_id)
    {
        $homeworks = Homework::where('class_id', $class_id)->where('status', 1)->with('assignment')->get();

        $assignments = $homeworks->map(function ($homework) {
            return [
                'id' => $homework->assignment ? $homework->assignment->id : $homework->id,
                'name' => $homework->assignment ? $homework->assignment->title : $homework->title,
                'description' => $homework->assignment ? $homework->assignment->description : $homework->description,
                'level' => $homework->assignment ? $homework->assignment->level : "Không có",
                'totalScore' => $homework->assignment ? $homework->assignment->totalScore : $homework->score,
                'specialized' => $homework->assignment ? $homework->assignment->specialized : "Không có",
                'subject' => $homework->assignment ?  $homework->assignment->subject : "Không có",
                'topic' => $homework->assignment ? $homework->assignment->topic : "Không có",
                'type' => $homework->type,
            ];
        });

        return response()->json([
            'assignments' => $assignments,
        ], Response::HTTP_OK);
    }

    public function CreateAssignment(Request $request)
    {
        $rules = [
            //assignment
            'title' => 'string',
            'description' => 'string',
            'status' => 'in:closed,published,private,draft',
            'level' => 'string',
            'totalScore' => 'integer',
            'specialized' => 'string',
            'subject' => 'string',
            'topic' => 'string',
            //question
            'questions' => 'json',
            // 'questions.*.question' => 'string',
            // 'questions.*.score' => 'integer',
            // 'questions.*.choices' => 'array',
            // 'questions.*.choices.*.choice' => 'string',
            // 'questions.*.choices.*.is_correct' => 'boolean',
            //homework
            'class_id' => 'required|integer',
            'type' => 'required|in:link,quiz',
            'title' => 'string',
            'score' => 'integer',
            'start_datetime' => 'required|String',
            'due_datetime' => 'required|String',
            'duration' => 'required|integer',
            'auto_grade' => 'String',
            'homework_status' => 'required|integer',
        ];

        $messages = [
            'title.string' => 'title phải là chuỗi',
            'description.string' => 'description phải là chuỗi',
            'status.in' => 'status phải là closed, published, private hoặc draft',
            'level.string' => 'level phải là chuỗi',
            'totalScore.integer' => 'totalScore phải là số nguyên',
            'specialized.string' => 'specialized phải là chuỗi',
            'subject.string' => 'subject phải là chuỗi',
            'topic.string' => 'topic phải là chuỗi',
            'questions.json' => 'questions phải là json',
            // 'questions.*.question.string' => 'questions.*.question phải là chuỗi',
            // 'questions.*.score.integer' => 'questions.*.score phải là số nguyên',
            // 'questions.*.choices.array' => 'questions.*.choices phải là mảng',
            // 'questions.*.choices.*.choice.string' => 'questions.*.choices.*.choice phải là chuỗi',
            // 'questions.*.choices.*.is_correct.boolean' => 'questions.*.choices.*.is_correct phải là boolean',
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
        if ($request->type == 'quiz') {
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

        $homework = new Homework();
        $homework->class_id = $request->class_id;
        $homework->assignment_id = $request->type == 'link' ? null : $assignment->id;
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

        return response()->json([
            'message' => 'Tạo đề thi thành công',
        ], Response::HTTP_CREATED);
    }

    public function SubmitAssignment(Request $request)
    {
        return response()->json([
            'message' => 'Nộp bài thi thành công',
        ], Response::HTTP_OK);
        // $user = Auth::user();
        // $assignment = Assignment::find($request->assignment_id);
        // $questions = $assignment->questions;
        // $answers = Answer::new();
        // $answers->question_id = $questions->id;
        // $answers->user_id = $user->id;
        // foreach ($questions as $question) {
        //     $choice =  $question->choices;
        // }
        // return response()->json([
        //     $request->
        // ], Response::HTTP_OK);
        // return response()->json([
        //     'message' => 'Nộp bài thi thành công',
        //     'data' => $answers,
        // ], Response::HTTP_OK);
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
}
