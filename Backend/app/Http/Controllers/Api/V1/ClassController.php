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
        $classes = Classes::with('teacher')->get();
        $classes = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'class_name' => $class->class_name,
                'class_description' => $class->class_description,
                'teacher_name' => $class->teacher,
                'created_at' => $class->created_at,
            ];
        });

        return response()->json([
            'classes' => $classes,
        ], 200);
    }
};
