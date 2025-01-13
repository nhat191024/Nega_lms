<?php

namespace App\Http\Controllers;

use App\Models\CourseAssignment;
use App\Models\Course;
use App\Models\CourseQuiz;
use App\Models\QuizPackage;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Models\Enrollment;
use Maatwebsite\Excel\Facades\Excel;

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
                'number_of_questions' => 'required|integer|min:5|max:100',
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
            $randomQuizIds = array_rand(array_flip($quizIdArray), min($request->number_of_questions, count($quizIdArray)));
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
                'number_of_questions' => 'required|integer|min:5|max:100',
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

            $randomQuizIds = array_rand(array_flip($quizIdArray), min($request->number_of_questions, count($quizIdArray)));
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

    public function show($id)
    {
        $course = Course::with(['enrollments.user'])
            ->findOrFail($id);

        $studentsNotInCourse = User::where('role_id', 3)
            ->whereDoesntHave('courseEnrollments', function ($query) use ($id) {
                $query->where('course_id', $id);
            })
            ->get();

        $quizPackages = QuizPackage::all();

        return view('course.show', compact('course', 'quizPackages', 'studentsNotInCourse'));
    }

    public function addStudent(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $studentIds = $request->input('student_ids');

        if (empty($studentIds)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một học sinh.');
        }

        foreach ($studentIds as $studentId) {
            $exists = DB::table('course_enrollments')
                ->where('course_id', $courseId)
                ->where('student_id', $studentId)
                ->exists();

            if (!$exists) {
                DB::table('course_enrollments')->insert([
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('courses.show', $courseId)
            ->with('success', 'Học sinh đã được thêm vào khóa học.');
    }


    public function importConfirm(Request $request, $course_id)
    {
        $students = $request->input('students');
        $successMessages = [];
        $errorMessages = [];

        if (empty($students)) {
            return response()->json([
                'success' => false,
                'errorMessages' => ['Không có học sinh nào được gửi để nhập.'],
            ], 422);
        }

        foreach ($students as $studentData) {
            $student = User::where('email', $studentData['email'])->first();

            if ($student && $student->role_id == 3) {
                $enrollmentExists = DB::table('course_enrollments')
                    ->where('course_id', $course_id)
                    ->where('student_id', $student->id)
                    ->exists();

                if (!$enrollmentExists) {
                    DB::table('course_enrollments')->insert([
                        'course_id' => $course_id,
                        'student_id' => $student->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $successMessages[] = "Học sinh {$student->name} ({$student->email}) đã được thêm thành công.";
                } else {
                    $errorMessages[] = "Học sinh {$student->name} ({$student->email}) đã tồn tại trong khóa học.";
                }
            } else {
                $errorMessages[] = "Học sinh với email {$studentData['email']} không tồn tại hoặc không phải là học sinh.";
            }
        }

        return response()->json([
            'success' => true,
            'successMessages' => $successMessages,
            'errorMessages' => $errorMessages,
        ]);
    }

    public function downloadTemplate()
    {
        $headers = ['STT', 'Tên học sinh', 'Email'];

        $data = [
            [1, 'Tên học sinh 1', 'email1@example.com'],
            [2, 'Tên học sinh 2', 'email2@example.com'],
            [3, 'Tên học sinh 3', 'email3@example.com'],
        ];

        $dataWithHeaders = array_merge([$headers], $data);

        $fileName = 'student_import_template.xlsx';

        return Excel::download(new class($dataWithHeaders) implements FromArray {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }
        }, $fileName);
    }

    public function removeStudent(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $studentIds = $request->input('student_ids');

        foreach ($studentIds as $studentId) {
            $exists = DB::table('course_enrollments')
                ->where('course_id', $courseId)
                ->where('student_id', $studentId)
                ->exists();

            if ($exists) {
                DB::table('course_enrollments')
                    ->where('course_id', $courseId)
                    ->where('student_id', $studentId)
                    ->delete();
            }
        }

        return redirect()->route('courses.show', $courseId)
            ->with('success', 'Học sinh đã được xóa khỏi khóa học.');
    }
}
