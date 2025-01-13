<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Submission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClassAssignment;
use App\Models\ClassSubmit;
use Fruitcake\Cors\CorsService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClassController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $enrolledClassIds = $user->enrollments->pluck('class_id')->toArray();
            $classes = Classes::with('teacher', 'categories')
                ->whereIn('id', $enrolledClassIds)
                ->where('status', "published")
                ->get();
            $classes = $classes->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'description' => $class->description,
                    'teacherName' => $class->teacher->name,
                    'categories' => $class->categories->map(function ($category) {
                        return $category->name;
                    }),
                    'createdAt' => $class->created_at,
                ];
            });
        } else if ($user->role_id == 2) {
            $classes = Classes::where('teacher_id', $user->id)->where('status', "published")->with('categories')->get();
            $classes = $classes->map(function ($class) use ($user) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'description' => $class->description,
                    'teacherName' => $user->name,
                    'categories' => $class->categories->map(function ($category) {
                        return $category->name;
                    }),
                    'createdAt' => $class->created_at,
                ];
            });
        }

        return response()->json([
            'classes' => $classes,
        ], Response::HTTP_OK);
    }

    public function getClassById($id)
    {
        $class = Classes::where('id', $id)->with('teacher')->first();

        return response()->json([
            'id' => $class->id,
            'code' => $class->code,
            'name' => $class->name,
            'description' => $class->description,
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
            'categories' => $class->categories->map(function ($category) {
                return $category->name;
            }),
            'createdAt' => $class->created_at,
        ];

        return response()->json([
            'classes' => [$data],
        ], Response::HTTP_OK);
    }

    public function getClassAssignmentPoint($id)
    {
        $assignments = ClassAssignment::where('class_id', $id)->with('submits.user')->get();
        $enrollment = Enrollment::where('class_id', $id)->with('student')->get();

        $assignmentName = $assignments->map(function ($assignment) {
            return $assignment->title;
        })->values();

        $students = $enrollment->map(function ($student) {
            return $student->student->name;
        });

        $assignmentPoint = $students->map(function ($studentName) use ($assignments) {
            $points = $assignments->map(function ($assignment) use ($studentName) {
                $submit = $assignment->submits->firstWhere('user.name', $studentName);
                if ($assignment->type == 'quiz') {
                    return [
                        'score' => $submit ? $submit->score : 0,
                    ];
                } else {
                    return [
                        'score' => $submit ? "Đã nộp bài" : "Chưa nộp bài",
                    ];
                }
            })->values();

            return [
                'name' => $studentName,
                'points' => $points
            ];
        })->values();

        return response()->json([
            'assignmentName' => $assignmentName,
            'assignmentPoint' => $assignmentPoint,
        ], Response::HTTP_OK);
    }

    public function getStudentAssignmentPoint($id)
    {
        $user = Auth::user();
        $assignments = ClassAssignment::where('class_id', $id)->with('submits', 'quizzes')->get();

        $respond = $assignments->map(function ($assignment) {
            return [
                'title' => $assignment->title,
                'type' => $assignment->type,
                'due_date' => $assignment->due_date,
                'score' => $assignment->submits->where('student_id', Auth::id())->first()->score ?? 0,
                'total_score' => $assignment->type == 'quiz' ? $assignment->quizzes->count() : 0,
                'handed_in' => $assignment->submits->where('student_id', Auth::id())->first() ? true : false,
            ];
        });

        return response()->json(
            $respond,
            Response::HTTP_OK
        );
    }
}
