<?php
namespace App\Http\Controllers\Api\v1;

use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassController extends Controller
{
    public function index()
    {
        // Lấy tất cả các lớp học và eager load thông tin giáo viên
        $classes = Classes::with('teacher')->get();  // Lấy tất cả lớp với thông tin giáo viên

        // Chuyển đổi thành mảng để thay thế teacher_id bằng tên giáo viên và xóa teacher_id
        $classes = $classes->map(function ($class) {
            // Thêm tên giáo viên và loại bỏ teacher_id
            $class->teacher_name = $class->teacher ? $class->teacher->name : 'Chưa có giáo viên';  // Thêm tên giáo viên
            unset($class->teacher_id);  // **Xóa teacher_id**
            unset($class->teacher);    // **Xóa relationship teacher (nếu có)**

            return $class;
        });

        // Lấy học sinh chưa có lớp
        $studentsNotInClass = function ($classID) {
            return User::where('role_id', 3)
                ->whereDoesntHave('enrollments', function ($query) use ($classID) {
                    $query->where('class_id', $classID);
                })
                ->get();
        };

        // Lấy tất cả các giáo viên
        $teachersNotInClass = User::where('role_id', 2)->get();

        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'classes' => $classes,
            'studentsNotInClass' => $studentsNotInClass,
            'teachersNotInClass' => $teachersNotInClass
        ]);
    }

    public function getStudentsNotInClass($classID)
    {
        // Lấy học sinh chưa có lớp cụ thể
        $studentsNotInClass = User::where('role_id', 3)
            ->whereDoesntHave('enrollments', function ($query) use ($classID) {
                $query->where('class_id', $classID);
            })
            ->get();

        return response()->json([
            'studentsNotInClass' => $studentsNotInClass
        ]);
    }

    public function getTeachersNotInClass()
    {
        // Lấy tất cả giáo viên chưa có lớp
        $teachersNotInClass = User::where('role_id', 2)->get();

        return response()->json([
            'teachersNotInClass' => $teachersNotInClass
        ]);
    }
}
