<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use App\Models\QuizPackage;
use App\Models\QuizPackageCategory;
use App\Models\Quiz;
use App\Models\Choice;

class QuizPackageController extends Controller
{
    public function getQuizPackageForTeacher()
    {
        $user = Auth::user();

        $quizPackages = QuizPackage::where(
            function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('type', 'public');
            }
        )->where('status', 'published')->with('creator', 'categories', 'quizzes')->get();

        $quizPackages = $quizPackages->map(function ($quizPackage) {
            return [
                'id' => $quizPackage->id,
                'title' => $quizPackage->title,
                'description' => $quizPackage->description,
                'status' => $quizPackage->status,
                'type' => $quizPackage->type,
                'creator' => $quizPackage->creator->name,
                'totalQuizzes' => $quizPackage->quizzes->count(),
                'categories' => $quizPackage->categories->map(function ($category) {
                    return $category->name;
                }),
                'createdAt' => $quizPackage->created_at->format('H:i d m Y'),
            ];
        });

        return response()->json($quizPackages, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $quizPackages = QuizPackage::create([
            'creator_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'quiz_id_range' => 0,
            'status' => 'published',
            'type' => $request->type,
        ]);

        QuizPackageCategory::create([
            'quiz_package_id' => $quizPackages->id,
            'category_id' => 1,
        ]);

        $questions = json_decode($request->questions, true);
        foreach ($questions as $question) {
            $quiz = Quiz::create([
                'question' => $question['question'],
                'quiz_package_id' => $quizPackages->id,
            ]);

            foreach ($question['choices'] as $choice) {
                Choice::create([
                    'quiz_id' => $quiz->id,
                    'choice' => $choice['choice'],
                    'is_correct' => $choice['is_correct'],
                ]);
            }
        }

        return response()->json('ok', Response::HTTP_CREATED);
    }
}
