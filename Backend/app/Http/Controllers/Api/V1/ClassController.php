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
        $classes = Classes::with('teacher')->get();

        // Chuyển đổi thành mảng để thay thế teacher_id bằng tên giáo viên
        $classes = $classes->map(function ($class) {
            // Thêm teacher_name và xóa teacher_id
            return [
                'id' => $class->id,
                'class_name' => $class->class_name,
                'class_description' => $class->class_description,
                'teacher_name' => $class->teacher ? $class->teacher->name : 'Chưa có giáo viên',
                'created_at' => $class->created_at,
                'updated_at' => $class->updated_at,
            ];
        });

        return response()->json([
            'classes' => $classes,
        ]);
    }
};
