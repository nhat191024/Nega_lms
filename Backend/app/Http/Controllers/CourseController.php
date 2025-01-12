<?php

namespace App\Http\Controllers;

use App\Models\CourseAssignment;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query();

        if ($request->filled('search')) {
            $courses->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $courses = $courses->paginate($request->get('per_page', 25));

        return view('course.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:courses',
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        Course::create($request->all());

        return redirect()->route('course.index')->with('success', 'Khóa học đã được tạo thành công.');
    }

    public function show($id)
    {
        $course = Course::with(['enrollments' => function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('role_id', 3);
            });
        }, 'enrollments.user'])->findOrFail($id);

        return view('course.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:courses,code,' . $id,
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $course = Course::findOrFail($id);
        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Khóa học đã được cập nhật thành công.');
    }

    public function storeAssignment(Request $request, $courseId)
{
    $request->validate([
        'title' => 'required',
        'video_url' => 'required|url',
        'description' => 'required',
        'duration' => 'nullable|integer',
    ]);

    $course = Course::findOrFail($courseId);

    $assignment = new CourseAssignment([
        'course_id' => $courseId,
        'title' => $request->title,
        'video_url' => $request->video_url,
        'description' => $request->description,
        'duration' => $request->duration,
    ]);

    $course->assignments()->save($assignment);

    return redirect()->route('courses.show', $courseId)->with('success', 'Bài học đã được thêm thành công.');
}

}
