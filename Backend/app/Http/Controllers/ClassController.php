<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassAssignment;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::all();
        $studentsNotInClass = [];

        // Lấy danh sách học sinh chưa có trong lớp
        foreach ($classes as $class) {
            $studentsNotInClass[$class->id] = User::where('role_id', 3)
                ->whereDoesntHave('enrollments', function ($query) use ($class) {
                    $query->where('class_id', $class->id);
                })
                ->get();
        }

        $teachersNotInClass = User::where('role_id', 2)->get();

        return view('class.index', compact('classes', 'studentsNotInClass', 'teachersNotInClass'));
    }

    public function addStudentToClass(Request $request)
    {
        // Kiểm tra nếu student_id và class_id không được chọn (null)
        if (is_null($request->student_id) || is_null($request->class_id)) {
            return redirect()->back()->with('error', 'Vui lòng chọn học sinh và lớp học!');
        }

        $studentID = $request->student_id;
        $classID = $request->class_id;

        $student = User::find($studentID);
        $class = Classes::find($classID);

        if (!$student) {
            return redirect()->back()->with('error', 'Học sinh không tồn tại!');
        }

        if (!$class) {
            return redirect()->back()->with('error', 'Lớp học không tồn tại!');
        }

        $enrollment = Enrollment::create([
            'student_id' => $studentID,
            'class_id' => $classID,
        ]);

        if ($enrollment) {
            return redirect()->back()->with('success', 'Thêm học sinh thành công');
        } else {
            return redirect()->back()->with('error', 'Thêm học sinh thất bại');
        }
    }

    public function removeStudentFromAClass($class_id, $student_id)
    {
        $enrollment = Enrollment::where('class_id', $class_id)->where('student_id', $student_id)->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->back()->with('success', 'Học sinh đã được xóa khỏi lớp.');
        }
        return redirect()->back()->with('error', 'Không tìm thấy học sinh này trong lớp.');
    }

    public function addNewClass(Request $request)
    {
        $request->validate(
            [
                'code' => 'required|string|max:255|unique:classes,code',
                'name' => 'required|string|max:255|unique:classes,name',
                'description' => 'required|string|max:500',
                'teacher_id' => 'required|integer|exists:users,id',
            ],
            [
                'code.required' => 'Vui lòng nhập mã lớp',
                'code.unique' => 'Mã lớp đã tồn tại, vui lòng chọn mã khác',
                'name.required' => 'Vui lòng nhập tên lớp!',
                'name.unique' => 'Lớp học đã tồn tại, vui lòng nhập tên khác',
                'description.required' => 'Vui lòng nhập mô tả lớp!',
                'teacher_id.required' => 'Vui lòng chọn giảng viên!',
                'teacher_id.exists' => 'Giảng viên không tồn tại!',
            ]
        );

        $class = Classes::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('classes.index')->with('success', 'Thêm lớp học thành công');
    }

    public function hideClass(Request $request)
    {
        $class_id = $request->class_id;
        $class = Classes::find($class_id);

        if (!$class) {
            return redirect()->back()->with('error', 'Lớp không tồn tại');
        }

        $newStatus = $class->status === 'published' ? 'closed' : 'published';
        $class->update(['status' => $newStatus]);

        $message = $newStatus === 'closed' ? 'Đã ẩn lớp học!' : 'Đã hiển thị lớp học!';
        return redirect()->back()->with('success', $message);
    }

    public function editClass($id)
    {
        $class = Classes::findOrFail($id);
        $teachersNotInClass = User::where('role_id', 2)->get();
        return view('class.edit', compact('class', 'teachersNotInClass'));
    }

    public function updateClass(Request $request, $id)
    {
        $request->validate(
            [
                'code' => 'required|string|max:255|unique:classes,code,' . $id,
                'name' => 'required|string|max:255|unique:classes,name,' . $id,
                'description' => 'required|string|max:500',
                'teacher_id' => 'required|integer|exists:users,id',
            ],
            [
                'code.required' => 'Vui lòng nhập mã lớp!',
                'code.unique' => 'Mã lớp đã tồn tại, vui lòng chọn mã khác',
                'name.required' => 'Vui lòng nhập tên lớp!',
                'name.unique' => 'Lớp học đã tồn tại, vui lòng nhập tên khác',
                'description.required' => 'Vui lòng nhập mô tả lớp!',
                'teacher_id.required' => 'Vui lòng chọn giảng viên!',
                'teacher_id.exists' => 'Giảng viên không tồn tại!',
            ]
        );

        $class = Classes::findOrFail($id);
        $class->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('classes.index')->with('success', 'Lớp học đã được cập nhật.');
    }

    public function show($id, $assignment_id = null)
    {
        $class = Classes::with(['teacher', 'students', 'assignments.quizzes.choices', 'assignments.submits.student'])->findOrFail($id);
        $studentsNotInClass = User::where('role_id', 3)
            ->whereDoesntHave('enrollments', function ($query) use ($class) {
                $query->where('class_id', $class->id);
            })
            ->get();

        $assignment = $assignment_id ? ClassAssignment::with('quizzes.choices', 'submits.student')->findOrFail($assignment_id) : null;

        return view('class.show', compact('class', 'studentsNotInClass', 'assignment'));
    }

    public function assignmentDetailsJson($assignment_id)
    {
        $assignment = ClassAssignment::with('quizzes.choices', 'submits.student')->findOrFail($assignment_id);

        $questionsHtml = view('partials.assignment_questions', compact('assignment'))->render();
        $scoresHtml = view('partials.assignment_scores', compact('assignment'))->render();

        return response()->json([
            'questionsHtml' => $questionsHtml,
            'scoresHtml' => $scoresHtml,
        ]);
    }
}
