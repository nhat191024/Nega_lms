<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::all();
        $studentsNotInClass = function ($classID) {
            return User::where('role_id', 3)
                ->whereDoesntHave('enrollments', function ($query) use ($classID) {
                    $query->where('class_id', $classID);
                })
                ->get();
        };
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
                'className' => 'required|string|max:255',
                'classDescription' => 'required|string|max:500',
                'teacherID' => 'required|integer|exists:users,id',
            ],
            [
                'className.required' => 'Vui lòng nhập tên lớp!',
                'classDescription.required' => 'Vui lòng nhập mô tả lớp!',
                'teacherID.required' => 'Vui lòng chọn giảng viên!',
                'teacherID.exists' => 'Giảng viên không tồn tại!',
            ]
        );

        $className = $request->className;
        $classDescription = $request->classDescription;
        $teacherID = $request->teacherID;

        $existingClass = Classes::where('class_name', $className)->first();
        if ($existingClass) {
            return redirect()->back()->withInput()->with('error', 'Lớp học đã tồn tại!');
        }

        $class = Classes::create([
            'class_code' => Str::upper(Str::random(5)),
            'class_name' => $className,
            'class_description' => $classDescription,
            'teacher_id' => $teacherID,
        ]);

        return redirect()->route('classes.index')->with('success', 'Thêm lớp học thành công');
    }

    public function hideClass(Request $request)
    {
        $class_id = $request->class_id;
        $updateStatus = Classes::find($class_id);

        if (!$updateStatus) {
            return redirect()->back()->with('error', 'Lớp không tồn tại');
        }

        $newStatus = $updateStatus->status ? 0 : 1;
        $updateStatus->update(['status' => $newStatus]);

        $message = $newStatus === 0 ? 'Đã ẩn lớp học!' : 'Đã hiển thị lớp học!';
        return redirect()->back()->with('success', $message);
    }
    public function editClass($class_id)
    {
        $class = Classes::findOrFail($class_id);
        $teachersNotInClass = User::where('role_id', 2)->get();
        return view('class.edit', compact('class', 'teachersNotInClass'));
    }

    public function updateClass(Request $request, $class_id)
    {
        $request->validate(
            [
                'className' => 'required|string|max:255',
                'classDescription' => 'required|string|max:500',
                'teacherID' => 'required|integer|exists:users,id',
            ],
            [
                'className.required' => 'Vui lòng nhập tên lớp!',
                'classDescription.required' => 'Vui lòng nhập mô tả lớp!',
                'teacherID.required' => 'Vui lòng chọn giảng viên!',
                'teacherID.exists' => 'Giảng viên không tồn tại!',
            ]
        );

        $class = Classes::findOrFail($class_id);
        $class->update([
            'class_name' => $request->className,
            'class_description' => $request->classDescription,
            'teacher_id' => $request->teacherID,
        ]);

        return redirect()->route('classes.index')->with('success', 'Lớp học đã được cập nhật.');
    }
}
