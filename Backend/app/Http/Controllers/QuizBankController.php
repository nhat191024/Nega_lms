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
            // Tối ưu tải dữ liệu: sử dụng select() để chỉ lấy các trường cần thiết và paginate() để phân trang
            $quizBank = QuizPackage::with('quizzes', 'creator', 'categories')
                ->orderBy('created_at', 'DESC')
                ->paginate(10); // Phân trang để giảm tải

            $categories = Category::with('parent', 'children')
                ->orderBy('created_at', 'DESC')
                ->paginate(10); // Phân trang để giảm tải

            $quizzes = Quiz::with('quizPackage', 'choices')
                ->orderBy('created_at', 'DESC')
                ->paginate(10); // Phân trang để giảm tải

            return view('QuizBank.index', compact('quizBank', 'categories', 'quizzes'));
        } else {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
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
                    'quiz_id_range' => $request->quiz_id_range,
                    'status' => $request->status,
                ]);

                foreach ($request->categories as $category) {
                    QuizPackageCategory::insert([
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

    public function updateQuizBank(Request $request)
    {
        if (Auth::check()) {
            // try {
            $updateQuizBank = QuizPackage::find($request->quiz_id);

            if (!$updateQuizBank) {
                return redirect()->route('quiz-bank.index')->with('error', 'Kho quiz không tồn tại!');
            }

            $updateQuizBank->update([
                'creator_id' => Auth::id(),
                'title' => $request->quiz_name,
                'description' => $request->quiz_description,
                'quiz_id_range' => $request->quiz_id_range,
                'status' => $request->status,
            ]);

            QuizPackageCategory::where('quiz_package_id', $updateQuizBank->id)->delete();

            if ($request->categories) {
                foreach ($request->categories as $category) {
                    QuizPackageCategory::create([
                        'quiz_package_id' => $updateQuizBank->id,
                        'category_id' => $category,
                    ]);
                }
            }

            return redirect()->route('quiz-bank.index')->with('success', 'Cập nhật kho quiz thành công!');
            // } catch (\Throwable $th) {
            return redirect()->route('quiz-bank.index')->with('error', 'Cập nhật kho quiz thất bại!')->with('message', $th->getMessage());
            // }
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function hiddenQuizBank($id)
    {
        if (Auth::check()) {
            $hiddenQuizBank = QuizPackage::where('id', $id)->update(['status' => 'private']);

            if ($hiddenQuizBank) {
                return redirect()->route('quiz-bank.index')->with('success', 'Ẩn kho quiz thành công!');
            } else {
                return redirect()->route('quiz-bank.index')->with('error', 'Ẩn kho quiz thất bại!');
            }
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function showQuizBank($id)
    {
        if (Auth::check()) {
            $showQuizBank = QuizPackage::where('id', $id)->update(['status' => 'published']);

            if ($showQuizBank) {
                return redirect()->route('quiz-bank.index')->with('success', 'Hiển thị kho quiz thành công!');
            } else {
                return redirect()->route('quiz-bank.index')->with('error', 'Hiển thị kho quiz thất bại!');
            }
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function addQuestion(Request $request)
    {
        if (Auth::check()) {
            // dd($request->all());
            try {
                $request->validate([
                    'question_name' => 'required|string|max:255',
                    'anwser_name_1' => 'max:255',
                    'anwser_name_2' => 'max:255',
                    'anwser_name_3' => 'max:255',
                    'anwser_name_4' => 'max:255',
                    'anwser' => 'required|in:1,2,3,4',
                ]);

                $addQuestion = Quiz::create([
                    'question' => $request->question_name,
                    'quiz_package_id' => $request->quiz_package_id,
                ]);

                $anwsers = [
                    [
                        'choice' => $request->input('anwser_name_1'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('anwser') == 1 ? 1 : 0,
                    ],
                    [
                        'choice' => $request->input('anwser_name_2'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('anwser') == 2 ? 1 : 0,
                    ],
                    [
                        'choice' => $request->input('anwser_name_3'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('anwser') == 3 ? 1 : 0,
                    ],
                    [
                        'choice' => $request->input('anwser_name_4'),
                        'quiz_id' => $addQuestion->id,
                        'is_correct' => $request->input('anwser') == 4 ? 1 : 0,
                    ],
                ];

                $anwsers = array_filter($anwsers, function ($anwser) {
                    return !empty($anwser['choice']);
                });

                if (empty($anwsers)) {
                    return redirect()->route('quiz-bank.index')->with('error', 'Vui lòng nhập ít nhất một câu trả lời.');
                }

                foreach ($anwsers as $anwser) {
                    $addQuestion->choices()->create($anwser);
                }

                return redirect()->route('quiz-bank.index')->with('success', 'Tạo câu hỏi thành công!');
            } catch (\Throwable $th) {
                return redirect()->route('quiz-bank.index')->with('error', 'Tạo câu hỏi thất bại!')->with('message', $th->getMessage());
            }
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function updateQuestion(Request $request)
    {
        if (Auth::check()) {
            try {
                $question = Quiz::with('quizPackage', 'choices')->findOrFail($request->question_id);

                $isUpdated = false;

                if ($request->question != $question->question) {
                    $question->question = $request->question;
                    $isUpdated = true;
                }

                foreach ($question->choices as $index => $choice) {
                    $index++;
                    $anwserName = 'anwser_' . $index;

                    if ($request->has($anwserName) && $request->$anwserName != $choice->choice) {
                        $choice->choice = $request->$anwserName;
                        $isUpdated = true;
                    }

                    if (isset($request->anwser)) {
                        $correctAnwser = $index == $request->anwser ? 1 : 0;
                        if ($choice->is_correct !== $correctAnwser) {
                            $choice->is_correct = $correctAnwser;
                            $isUpdated = true;
                        }
                    }
                }

                if ($isUpdated) {
                    $question->save();
                    foreach ($question->choices as $choice) {
                        $choice->save();
                    }
                    return redirect()->route('quiz-bank.index')->with('success', 'Cập nhật câu hỏi thành công');
                }

                return redirect()->route('quiz-bank.index')->with('error', 'Không có thay đổi nào để cập nhật');
            } catch (\Throwable $th) {
                return back()->with('error', 'Có lỗi xảy ra trong quá trình cập nhật. Vui lòng thử lại.');
            }
        }

        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thực hiện hành động này');
    }

    public function deleteQuestion(Request $request)
    {
        if (Auth::check()) {
            $id = $request->question_id;
            $deleteQuestion = Quiz::findOrFail($id);
            if ($deleteQuestion) {
                $deleteQuestion->delete();
                $choices = Choice::where('quiz_id', $deleteQuestion->id)->get();
                if ($choices) {
                    foreach ($choices as $choice) {
                        $choice->delete();
                    }
                    return redirect()->route('quiz-bank.index')->with('success', 'Xóa câu hỏi thành công!');
                }
            }
            return redirect()->route('quiz-bank.index')->with('error', 'Xóa câu hỏi thất bại!');
        }
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thực hiện hành động này');
    }
}
