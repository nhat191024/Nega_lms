<?php

namespace App\Http\Controllers;

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
        $studentID = $request->student_id;
        $classID = $request->class_id;
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

    public function removeStudentFormAClass($class_id, $student_id)
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
                'classDescription.string' => 'Mô tả phải là 1 chuỗi!',
                'classDescription.max' => 'Không nhập quá 500 ký tự!',
                'teacherID.required' => 'Vui lòng chọn giảng viên!',
                'teacherID.exists' => 'Giảng viên không tồn tại!',
            ],
        );

        $className = $request->className;
        $classDescription = $request->classDescription;
        $teacherID = $request->teacherID;

        $classes = Classes::create([
            'class_name' => $className,
            'class_description' => $classDescription,
            'teacher_id' => $teacherID,
        ]);

        if ($classes) {
            return redirect()->route('classes.index')->with('success', 'Thêm lớp học thành công');
        } else {
            return redirect()->back()->with('error', 'Thêm lớp học thất bại');
        }
    }
    public function hideClassFormWebsite(Request $request)
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
}
