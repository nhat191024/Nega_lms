<?php

namespace App\Http\Controllers;
use App\Models\Classes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;




class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classes::all();
        return view('class.index', compact('classes'));

    /**
     * Show the form for creating a new resource.
     */
    }
    public function create()
    {
        $classes = Classes::all();
        return view('class.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'class_name' => 'required|string|max:255',
            'class_description' => 'nullable|string',
            'teacher_id' => 'required|exists:classes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Tạo lớp học mới
        $classes = Classes::create([
            'class_name' => $request->class_name,
            'class_description' => $request->class_description,
            'teacher_id' => $request->teacher_id,
        ]);

        // Trả về dữ liệu lớp học mới được tạo
        return redirect()->route('classes.index')->with('success', 'Class created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $classes = Classes::findOrFail($id);
        $teachers = Teacher::all(); // Lấy danh sách giáo viên từ bảng `teachers`
        return view('class.edit', compact('classes', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $class = Classes::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'class_name' => 'required|string|max:255',
        'class_description' => 'nullable|string',
        'teacher_id' => 'required|exists:teachers,id', // xác nhận rằng teacher_id tồn tại trong bảng teachers
    ]);

    if ($validator->fails()) {
        return redirect()->route('classes.edit', ['id' => $class->id])
            ->withErrors($validator)
            ->withInput();
    }

    $data = [
        'class_name' => $request->input('class_name'),
        'class_description' => $request->input('class_description'),
        'teacher_id' => $request->input('teacher_id'), // Đảm bảo teacher_id không null
    ];

    $class->update($data);

    return redirect()->route('classes.index')->with('success', 'Class updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function status(string $id)
    {
        $classes = Classes::findOrFail($id);
        $classes->status = !$classes->status;
        $classes->save();

        return redirect()->route('classes.index')->with('success', 'Trạng thái người dùng đã được cập nhật!');
    }

    public function destroy(string $id)
    {
        //
    }
}
