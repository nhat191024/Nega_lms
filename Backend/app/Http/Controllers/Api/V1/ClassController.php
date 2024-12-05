<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Classes;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::with('teacher')->get();

        $classes = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'name' => $class->class_name,
                'description' => $class->class_description,
                'teacherName' => $class->teacher ? $class->teacher->name : 'Chưa có giáo viên',
                'createdAt' => $class->created_at,
            ];
        });

        return response()->json([
            'classes' => $classes,
        ], 200);
    }

    public function getStudentClasses()
    {
        $user = Auth::user();
        $enrollments = $user->enrollments;

        if ($enrollments->isEmpty()) {
            return response()->json(['message' => 'Học sinh này chưa tham gia lớp học nào.'], 404);
        }

        $classes = $enrollments->map(function ($enrollment) {
            return [
                'class_id' => $enrollment->class->id,
                'class_name' => $enrollment->class->class_name,
                'class_description' => $enrollment->class->class_description,
                'teacher_name' => $enrollment->class->teacher->name ?? 'Chưa có giáo viên',
            ];
        });

        return response()->json($classes, 200);
    }
}
