<?php

namespace App\Http\Controllers;

use App\Models\CourseAssignment;
use App\Models\Course;
use App\Models\CourseQuiz;
use App\Models\QuizPackage;
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
        }, 'enrollments.user'])
        ->orderBy('created_at', 'DESC')
        ->findOrFail($id);
        $quizPackages = QuizPackage::all();
        $courseAssignment = QuizPackage::all();
        // foreach ($course->assignments as $index => $assignment) {
        //     dd($assignment);
        //     dd($assignment->courseQuizzes);
        // }
        
        return view('course.show', compact('course', 'quizPackages'));
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
        try {
            $request->validate([
                'title' => 'required',
                'video_url' => 'required|url',
                'description' => 'required',
                'duration' => 'nullable|integer',
                'number_of_questions' => 'required|integer|min:10|max:100',
                'quiz_select' => 'required'
            ]);
    
            $course = Course::findOrFail($courseId);
    
            $assignment = new CourseAssignment([
                'course_id' => $course->id,
                'title' => $request->title,
                'video_url' => $request->video_url,
                'description' => $request->description,
                'duration' => $request->duration,
            ]);
    
            $course->assignments()->save($assignment);

            $quizPackage = QuizPackage::findOrFail($request->quiz_select);
            $quizIdArray = [];
            foreach ($quizPackage->quizzes as $quiz) {
                $quizIdArray[] = $quiz->id;
            }
            $randomQuizIds = array_rand(array_flip($quizIdArray), min(10, count($quizIdArray)));
            $randomQuizIds = (array) $randomQuizIds;

            foreach ($randomQuizIds as $quizID) {
                CourseQuiz::insert([
                    'course_assignment_id' => $assignment->id,
                    'quiz_id' => $quizID,
                ]);
            }
    
            return redirect()->route('courses.show', $courseId)->with('success', 'Bài học đã được thêm thành công.');
        } catch (\Throwable $th) {
            return redirect()->route('courses.show', $courseId)->with('error', 'Bài học thêm thất bại.');
        }
    }

    public function updateAssignment(Request $request, $courseId, $assignmentId)
    {
        
        try {
            $request->validate([
                'title' => 'required',
                'video_url' => 'required|url',
                'description' => 'required',
                'duration' => 'nullable|integer',
                'number_of_questions' => 'required|integer|min:10|max:100',
                'quiz_select' => 'required'
            ]);
            
            $course = Course::findOrFail($courseId);
            $assignment = CourseAssignment::findOrFail($assignmentId);

            $assignment->title = $request->title;
            $assignment->video_url = $request->video_url;
            $assignment->description = $request->description;
            $assignment->duration = $request->duration;
            $assignment->save();

            $quizPackage = QuizPackage::findOrFail($request->quiz_select);
            $quizIdArray = [];
            foreach ($quizPackage->quizzes as $quiz) {
                $quizIdArray[] = $quiz->id;
            }

            $randomQuizIds = array_rand(array_flip($quizIdArray), min(10, count($quizIdArray)));
            $randomQuizIds = (array) $randomQuizIds;

            foreach ($assignment->courseQuizzes as $courseQuiz) {
                $courseQuiz->delete();
            }

            foreach ($randomQuizIds as $quizID) {
                CourseQuiz::insert([
                    'course_assignment_id' => $assignment->id,
                    'quiz_id' => $quizID,
                ]);
            }

            return redirect()->route('courses.show', $courseId)->with('success', 'Bài học đã được cập nhật thành công.');
        } catch (\Throwable $th) {
            return redirect()->route('courses.show', $courseId)->with('error', 'Cập nhật bài học thất bại.');
        }
    }

    public function deleteAssignment($courseId, $assignmentId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $assignment = CourseAssignment::findOrFail($assignmentId);

            $assignment->courseQuizzes()->delete(); 

            $assignment->delete();

            return redirect()->route('courses.show', $courseId)->with('success', 'Bài học đã được xóa thành công.');
        } catch (\Throwable $th) {
            return redirect()->route('courses.show', $courseId)->with('error', 'Xóa bài học thất bại.');
        }
    }

}
