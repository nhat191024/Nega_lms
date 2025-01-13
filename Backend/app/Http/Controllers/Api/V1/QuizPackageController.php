<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use App\Models\QuizPackage;

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
}
