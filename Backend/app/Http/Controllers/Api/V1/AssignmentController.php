<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

//model
use App\Models\Assignment;
use App\Models\Choice;
use App\Models\Question;

class AssignmentController extends Controller
{
    public function CreateAssignment(Request $request)
    {
        $rules = [
            'class_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:closed,published,private,draft',
            'level' => 'required|string',
            'duration' => 'required|date_format:H:i:s',
            'totalScore' => 'required|integer',
            'specialized' => 'required|string',
            'subject' => 'required|string',
            'topic' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'due_date' => 'required|date_format:Y-m-d H:i:s',
            'auto_grade' => 'required|boolean',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.duration' => 'required|string',
            'questions.*.score' => 'required|integer',
            'questions.*.choices' => 'required|array',
            'questions.*.choices.*.choice' => 'required|string',
            'questions.*.choices.*.is_correct' => 'required|boolean',
        ];

        $messages = [
            'class_id.required' => 'Trường class_id là bắt buộc.',
            'class_id.integer' => 'Trường class_id phải là một số nguyên.',
            'name.required' => 'Trường tên là bắt buộc.',
            'name.string' => 'Trường tên phải là chuỗi ký tự.',
            'description.string' => 'Trường mô tả phải là chuỗi ký tự.',
            'status.required' => 'Trường trạng thái là bắt buộc.',
            'status.in' => 'Trường trạng thái phải là một trong các giá trị: closed, published, private, draft.',
            'level.required' => 'Trường cấp độ là bắt buộc.',
            'level.string' => 'Trường cấp độ phải là chuỗi ký tự.',
            'duration.required' => 'Trường thời lượng là bắt buộc.',
            'duration.date_format' => 'Trường thời lượng phải theo định dạng HH:mm:ss.',
            'totalScore.required' => 'Trường tổng điểm là bắt buộc.',
            'totalScore.integer' => 'Trường tổng điểm phải là một số nguyên.',
            'specialized.required' => 'Trường chuyên ngành là bắt buộc.',
            'specialized.string' => 'Trường chuyên ngành phải là chuỗi ký tự.',
            'subject.required' => 'Trường môn học là bắt buộc.',
            'subject.string' => 'Trường môn học phải là chuỗi ký tự.',
            'topic.required' => 'Trường chủ đề là bắt buộc.',
            'topic.string' => 'Trường chủ đề phải là chuỗi ký tự.',
            'start_date.required' => 'Trường ngày bắt đầu là bắt buộc.',
            'start_date.date_format' => 'Trường ngày bắt đầu phải theo định dạng Y-m-d H:i:s.',
            'due_date.required' => 'Trường ngày kết thúc là bắt buộc.',
            'due_date.date_format' => 'Trường ngày kết thúc phải theo định dạng Y-m-d H:i:s.',
            'auto_grade.required' => 'Trường tự động chấm điểm là bắt buộc.',
            'auto_grade.boolean' => 'Trường tự động chấm điểm phải là giá trị boolean.',
            'questions.required' => 'Trường câu hỏi là bắt buộc.',
            'questions.array' => 'Trường câu hỏi phải là một mảng.',
            'questions.*.question.required' => 'Câu hỏi là bắt buộc.',
            'questions.*.question.string' => 'Câu hỏi phải là chuỗi ký tự.',
            'questions.*.duration.required' => 'Thời lượng câu hỏi là bắt buộc.',
            'questions.*.duration.string' => 'Thời lượng câu hỏi phải là chuỗi ký tự.',
            'questions.*.score.required' => 'Điểm câu hỏi là bắt buộc.',
            'questions.*.score.integer' => 'Điểm câu hỏi phải là một số nguyên.',
            'questions.*.choices.required' => 'Danh sách các lựa chọn là bắt buộc.',
            'questions.*.choices.array' => 'Danh sách các lựa chọn phải là một mảng.',
            'questions.*.choices.*.choice.required' => 'Nội dung lựa chọn là bắt buộc.',
            'questions.*.choices.*.choice.string' => 'Nội dung lựa chọn phải là chuỗi ký tự.',
            'questions.*.choices.*.is_correct.required' => 'Trạng thái đúng/sai của lựa chọn là bắt buộc.',
            'questions.*.choices.*.is_correct.boolean' => 'Trạng thái đúng/sai của lựa chọn phải là giá trị boolean.',
        ];

        $validated = Validator::make($request->all(), $rules, $messages);
        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $assignment = Assignment::create([
            'class_id' => $request->input('class_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'level' => $request->input('level'),
            'duration' => $request->input('duration'),
            'totalScore' => $request->input('totalScore'),
            'specialized' => $request->input('specialized'),
            'subject' => $request->input('subject'),
            'topic' => $request->input('topic'),
            'start_date' => $request->input('start_date'),
            'due_date' => $request->input('due_date'),
            'auto_grade' => $request->input('auto_grade'),
        ]);

        foreach ($request->input('questions') as $questionData) {
            $question = Question::create([
                'assignment_id' => $assignment->id,
                'question' => $questionData['question'],
                'duration' => $questionData['duration'],
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

        return response()->json([
            'message' => 'Tạo đề thi thành công',
            'data' => $assignment,
        ], Response::HTTP_CREATED);
    }
}
