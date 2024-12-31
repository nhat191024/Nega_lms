<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Choice;
use App\Models\Quiz;
use App\Models\QuizPackage;
use App\Models\QuizPackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizBankController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $quizBank = QuizPackage::with('quizzes', 'creator', 'categories')->get();
            $categories = Category::with('parent', 'children')->get();
            return view('QuizBank.index', compact('quizBank', 'categories'));
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function createQuizBank(Request $request)
    {
        if (Auth::check()) {
            try {
                $createQuizBank = QuizPackage::create([
                    'creator_id' => Auth::id(),
                    'title' => $request->quiz_name,
                    'description' => $request->quiz_description,
                    'quiz_id_range' => '1-30',
                    'status' => $request->status,
                ]);

                foreach ($request->categories as $category) {
                    $createQuizCategory = QuizPackageCategory::insert([
                        'quiz_package_id' => $createQuizBank->id,
                        'category_id' => $category,
                    ]);
                }
                return redirect()->route('quiz-bank.index')->with('success', 'Tạo kho quiz thành công!');
            } catch (\Throwable $th) {
                return redirect()->route('quiz-bank.index')->with('error', 'Tạo kho quiz thất bại!');
            }
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function addQuestion(Request $request)
    {
        if (Auth::check()) {
            // dd($request->all());
            // try {
                // Validate dữ liệu nhận từ form
                $request->validate([
                    'question_name' => 'required|string|max:255',
                    'answer' => 'required|in:1,2,3,4', // Phải chọn một câu trả lời
                ]);

                // Tạo câu hỏi mới
                $addQuestion = Quiz::create([
                    'question' => $request->question_name,
                    'quiz_package_id' => $request->quiz_package_id,
                ]);

                // Khởi tạo mảng câu trả lời với thông tin từ form
                $answers = [
                    [
                        'choice' => $request->input('answer_name_1'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('answer') == 1 ? 1 : 0,
                    ],
                    [
                        'choice' => $request->input('answer_name_2'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('answer') == 2 ? 1 : 0,
                    ],
                    [
                        'choice' => $request->input('answer_name_3'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('answer') == 3 ? 1 : 0,
                    ],
                    [
                        'choice' => $request->input('answer_name_4'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('answer') == 4 ? 1 : 0,
                    ],
                ];

                // Loại bỏ các câu trả lời không hợp lệ (câu trả lời trống)
                $answers = array_filter($answers, function ($answer) {
                    return !empty($answer['choice']); // Loại bỏ các câu trả lời không có tên
                });

                // Kiểm tra nếu không có câu trả lời hợp lệ
                if (empty($answers)) {
                    return redirect()->route('quiz-bank.index')->with('error', 'Vui lòng nhập ít nhất một câu trả lời.');
                }

                // Lưu câu trả lời vào bảng Choice
                foreach ($answers as $answer) {
                    $addQuestion->choices()->create($answer); // Đảm bảo liên kết câu trả lời với câu hỏi
                }

                return redirect()->route('quiz-bank.index')->with('success', 'Tạo câu hỏi thành công!');
            // } catch (\Throwable $th) {
                // return redirect()->route('quiz-bank.index')->with('error', 'Tạo câu hỏi thất bại!')->with('message', $th->getMessage());
            // }
        } else {
            return redirect()->route('admin.login');
        }
    }
}
