<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Classes;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function getStudentClasses($student_id)
    {
        // Lấy danh sách lớp học mà học sinh tham gia
        $enrollments = Enrollment::with('class.teacher')
            ->where('student_id', $student_id)
            ->get();

        // Định dạng dữ liệu trả về
        $classes = $enrollments->map(function ($enrollment) {
            return [
                'class_id' => $enrollment->class->id,
                'class_name' => $enrollment->class->class_name,
                'class_description' => $enrollment->class->class_description,
                'teacher_name' => $enrollment->class->teacher->name ?? 'Chưa có giáo viên',
            ];
        });

        // Kiểm tra nếu học sinh không tham gia lớp nào
        if ($classes->isEmpty()) {
            return response()->json(['message' => 'Học sinh này chưa tham gia lớp học nào.'], 404);
        }

        return response()->json(['student_id' => $student_id, 'classes' => $classes], 200);
    }
}
