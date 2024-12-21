<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Classes;
use App\Models\Enrollment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClassController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledClassIds = $user->enrollments->pluck('class_id')->toArray();

        $classes = Classes::with('teacher')->get();

        $classes = $classes->map(function ($class) use ($enrolledClassIds) {
            return [
                'id' => $class->id,
                'name' => $class->class_name,
                'description' => $class->class_description,
                'teacherName' => $class->teacher ? $class->teacher->name : 'Chưa có giáo viên',
                'createdAt' => $class->created_at,
                'isJoined' => in_array($class->id, $enrolledClassIds),
            ];
        });

        return response()->json([
            'classes' => $classes,
        ], Response::HTTP_OK);
    }

    public function getClassById($id)
    {
        $class = Classes::where('id', $id)->with('teacher')->first();

        return response()->json([
            'id' => $class->id,
            'code' => $class->class_code,
            'name' => $class->class_name,
            'description' => $class->class_description,
            'teacherName' => $class->teacher ? $class->teacher->name : 'Chưa có giáo viên',
            'createdAt' => $class->created_at
        ], Response::HTTP_OK);
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

    public function joinClass($classId)
    {
        $user = Auth::user();

        $enrollment = Enrollment::where('student_id', $user->id)->where('class_id', $classId)->first();

        if ($enrollment) {
            return response()->json(['message' => 'Bạn đã tham gia lớp học này.'], Response::HTTP_CONFLICT);
        }

        $enrollment = new Enrollment();
        $enrollment->student_id = $user->id;
        $enrollment->class_id = $classId;
        $enrollment->save();

        return response()->json(['message' => 'Tham gia lớp học thành công.'], Response::HTTP_CREATED);
    }

    public function searchClassByCode($classCode)
    {
        $class = Classes::where('class_code', $classCode)->first();

        if (!$class) {
            return response()->json(['message' => 'Không tìm thấy lớp học.'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $class->id,
            'name' => $class->class_name,
            'description' => $class->class_description,
            'teacherName' => $class->teacher ? $class->teacher->name : 'Chưa có giáo viên',
            'createdAt' => $class->created_at,
        ];

        return response()->json([
            'classes' => [$data],
        ], Response::HTTP_OK);
    }
}
