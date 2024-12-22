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
                'id' => $homework->assignment->id,
                'name' => $homework->assignment->title,
                'description' => $homework->assignment->description,
                'level' => $homework->assignment->level,
                'totalScore' => $homework->assignment->totalScore,
                'specialized' => $homework->assignment->specialized,
                'subject' => $homework->assignment->subject,
                'topic' => $homework->assignment->topic,
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
            'questions' => 'array',
            'questions.*.question' => 'string',
            'questions.*.score' => 'integer',
            'questions.*.choices' => 'array',
            'questions.*.choices.*.choice' => 'string',
            'questions.*.choices.*.is_correct' => 'boolean',
            //homework
            'class_id' => 'required|integer',
            'type' => 'required|in:homework,quiz',
            'title' => 'string',
            'link' => 'string',
            'start_datetime' => 'required|String',
            'due_datetime' => 'required|String',
            'duration' => 'required|integer',
            'auto_grade' => 'required|boolean',
            'status' => 'required|integer',
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
            'questions.array' => 'questions phải là mảng',
            'questions.*.question.string' => 'questions.*.question phải là chuỗi',
            'questions.*.score.integer' => 'questions.*.score phải là số nguyên',
            'questions.*.choices.array' => 'questions.*.choices phải là mảng',
            'questions.*.choices.*.choice.string' => 'questions.*.choices.*.choice phải là chuỗi',
            'questions.*.choices.*.is_correct.boolean' => 'questions.*.choices.*.is_correct phải là boolean',
            'class_id.required' => 'class_id không được để trống',
            'class_id.integer' => 'class_id phải là số nguyên',
            'type.required' => 'type không được để trống',
            'type.in' => 'type phải là homework hoặc quiz',
            'title.string' => 'title phải là chuỗi',
            'link.string' => 'link phải là chuỗi',
            'start_datetime.required' => 'start_datetime không được để trống',
            'start_datetime.string' => 'start_datetime phải là chuỗi',
            'due_datetime.required' => 'due_datetime không được để trống',
            'due_datetime.string' => 'due_datetime phải là chuỗi',
            'duration.required' => 'duration không được để trống',
            'duration.integer' => 'duration phải là số nguyên',
            'auto_grade.required' => 'auto_grade không được để trống',
            'auto_grade.boolean' => 'auto_grade phải là boolean',
            'status.required' => 'status không được để trống',
            'status.integer' => 'status phải là số nguyên',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);
        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = Auth::user();

        if ($request->input('title') != null) {
            $assignment = Assignment::create([
                'creator_id' => $user->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
                'level' => $request->input('level'),
                'totalScore' => $request->input('totalScore'),
                'specialized' => $request->input('specialized'),
                'subject' => $request->input('subject'),
                'topic' => $request->input('topic'),
            ]);

            foreach ($request->input('questions') as $questionData) {
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
        $homework->class_id = $request->input('class_id');
        $homework->assignment_id = $assignment ? $assignment->id : null;
        $homework->type = $request->input('type');
        $homework->link = $request->input('link') ?? null;
        $homework->title = $request->input('title') ?? null;
        $homework->start_datetime = $request->input('start_datetime');
        $homework->due_datetime = $request->input('due_datetime');
        $homework->duration = $request->input('duration');
        $homework->auto_grade = $request->input('auto_grade');
        $homework->status = $request->input('status');
        $homework->save();

        return response()->json([
            'message' => 'Tạo đề thi thành công',
            'data' => $assignment,
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
