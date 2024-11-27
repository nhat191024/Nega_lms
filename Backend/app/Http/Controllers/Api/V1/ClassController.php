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
        // Lấy danh sách tất cả các lớp học và thông tin giáo viên
        $classes = Classes::with('teacher')->get();

        // Định dạng lại dữ liệu trước khi trả về
        $classes = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'class_name' => $class->class_name,
                'class_description' => $class->class_description,
                'teacher_name' => $class->teacher ? $class->teacher->name : 'Chưa có giáo viên',
                'created_at' => $class->created_at,
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
