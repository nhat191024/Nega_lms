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
            return User::whereDoesntHave('enrollments', function ($query) use ($classID) {
                $query->where('class_id', $classID);
            })->get();
        };

        return view('class.index', compact('classes', 'studentsNotInClass'));
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

    public function hideClassFormWebsite(Request $request) {
        $class_id = $request->class_id;
        $updateStatus = Classes::find($class_id);
        $status = $updateStatus->status === 0 ? 1 : 0;
        $updateStatus->update([
            'status' => $status,
        ]);

        if ($updateStatus->status === 1) {
            return redirect()->back()->with('success', 'Đã hiển thị lớp');
        } elseif ($updateStatus->status === 0) {
            return redirect()->back()->with('success', 'Đã ẩn lớp');
        } else {
            return redirect()->back()->with('error', 'Vui lòng thử lại');
        }
    }
}
