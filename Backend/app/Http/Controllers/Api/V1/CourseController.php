<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $courses = Course::with('categories')->where('status', "published")->get();

        $courses = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'code' => $course->code,
                'name' => $course->name,
                'description' => $course->description,
                'categories' => $course->categories->map(function ($category) {
                    return $category->name;
                }),
                'createdAt' => $course->created_at,
            ];
        });

        return response()->json([
            'courses' => $courses,
        ], Response::HTTP_OK);
    }

    public function getCourseById($id)
    {
        $course = Course::with('categories')->find($id);

        return response()->json([
            'id' => $course->id,
            'code' => $course->code,
            'name' => $course->name,
            'description' => $course->description,
            'categories' => $course->categories->map(function ($category) {
                return $category->name;
            }),
            'createdAt' => $course->created_at,
        ], Response::HTTP_OK);
    }
}
