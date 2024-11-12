<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $classes = Classes::all();
        $studentsNotInClass = function ($classID) {
            return User::whereDoesntHave('enrollments', function ($query) use ($classID) {
                $query->where('class_id', $classID);
            })->get();
        };

        return view('enrollment.index', compact('classes', 'studentsNotInClass'));
    }

    public function create(Request $request)
    {
        $studentID = $request->student_id;
        $classID = $request->class_id;
        $enrollment = Enrollment::create([
            'student_id' => $studentID,
            'class_id' => $classID,
        ]);

        if ($enrollment) {
            return redirect()->route('enrollment.index')->with('success', 'Thêm học sinh thành công');
        } else {
            return redirect()->route('enrollment.index')->with('error', 'Thêm học sinh thất bại');
        }
    }


    public function destroy($class_id, $student_id)
    {
        $enrollment = Enrollment::where('class_id', $class_id)->where('student_id', $student_id)->first();

        // Kiểm tra nếu tìm thấy Enrollment
        if ($enrollment) {
            // Xóa bản ghi Enrollment
            $enrollment->delete();

            // Trả về thông báo thành công
            return redirect()->back()->with('success', 'Học sinh đã được xóa khỏi lớp.');
        }
        return redirect()->back()->with('error', 'Không tìm thấy học sinh này trong lớp.');
    }
}
