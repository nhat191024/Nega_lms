<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentQuiz;
use App\Models\Choice;
use App\Models\ClassAssignment;
use App\Models\Classes;
use App\Models\ClassSubmit;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            // dd($this->getScoresAndAchievements(1));
            $assignments = AssignmentQuiz::all();
            $students = User::where('role_id', 3)->get();
            $questions = Quiz::all();
            $classes = Classes::all();
            // $choices = Choice::all();
            $profile = User::findOrFail(Auth::id());
            return view('dashboard.index', compact(
                'assignments',
                'students',
                'questions',
                'profile',
                'classes'
            ));
        }

        Auth::logout();
        return redirect()
            ->route('admin.login')
            ->withErrors(['login' => 'Bạn không có quyền truy cập quản trị viên']);
    }

    public function getCompletionRate($classId)
    {
        // Lấy tổng số bài tập trong lớp
        $totalAssignments = ClassAssignment::where('class_id', $classId)->count();

        // Lấy danh sách ID học sinh tham gia lớp
        $studentIds = Enrollment::where('class_id', $classId)->pluck('student_id');

        // Đếm số bài tập đã được nộp
        $submissions = ClassSubmit::whereIn('student_id', $studentIds)
            ->whereHas('assignment', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->count();

        // Tính toán tỷ lệ hoàn thành
        $completionRate = 0;
        $totalStudents = $studentIds->count();

        if ($totalAssignments > 0 && $totalStudents > 0) {
            $completionRate = ($submissions / ($totalAssignments * $totalStudents)) * 100;
        }

        // Trả về kết quả dưới dạng JSON
        return response()->json([
            'class_id' => $classId,
            'completion_rate' => round($completionRate, 2) . '%',
        ]);
    }

    public function getUserClasses($userID)
    {
        $userId = $userID;

        // Lấy danh sách lớp học đã tham gia
        $classes = DB::table('enrollments')
            ->join('classes', 'enrollments.class_id', '=', 'classes.id')
            ->where('enrollments.student_id', $userId)
            ->select('classes.id', 'classes.name', 'classes.description', 'classes.teacher_id')
            ->get();

        // Lấy danh sách khóa học đã tham gia
        $courses = DB::table('course_enrollments')
            ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->where('course_enrollments.student_id', $userId)
            ->select('courses.id', 'courses.name', 'courses.description', 'courses.status')
            ->get();

        // Nếu không có lớp học hay khóa học, trả về thông báo rõ ràng
        if ($classes->isEmpty() && $courses->isEmpty()) {
            return response()->json(['message' => 'Không có lớp học hoặc khóa học nào'], 404);
        }

        return response()->json([
            'classes' => $classes,
            'courses' => $courses,
        ]);
    }


    public function getUserProgress($userID)
    {
        $userId = $userID;

        // Phân tích bài tập đã nộp
        $assignments = DB::table('class_submits')
            ->join('class_assignments', 'class_submits.class_assignment_id', '=', 'class_assignments.id')
            ->where('class_submits.student_id', $userId)
            ->select(
                DB::raw('COUNT(class_submits.id) as total_submitted'), // Tổng số bài tập đã nộp
                DB::raw('SUM(class_submits.score) as total_score') // Tổng điểm bài tập
            )
            ->first();

        // Phân tích bài kiểm tra
        $quizzes = DB::table('class_submits')
            ->join('class_assignments', 'class_submits.class_assignment_id', '=', 'class_assignments.id')
            ->join('assignment_quizzes', 'class_assignments.id', '=', 'assignment_quizzes.assignment_id')
            ->join('quizzes', 'assignment_quizzes.quiz_id', '=', 'quizzes.id')
            ->where('class_submits.student_id', $userId)
            ->select(
                DB::raw('COUNT(quizzes.id) as total_quizzes'), // Tổng số bài kiểm tra
                DB::raw('SUM(class_submits.score) as total_correct_answers') // Tổng điểm bài kiểm tra (giả sử điểm bài kiểm tra là số câu trả lời đúng)
            )
            ->first();

        return response()->json([
            'assignments' => $assignments,
            'quizzes' => $quizzes,
        ]);
    }

    public function getUserInteractions($userID)
    {
        $userId = $userID;

        // Lấy số lượng bình luận của người dùng
        $comments = DB::table('notification_comments')
            ->where('user_id', $userId)
            ->count();

        // Lấy số lượng thông báo mà người dùng tương tác
        $notifications = DB::table('class_notifications')
            ->join('notification_comments', 'class_notifications.id', '=', 'notification_comments.notification_id')
            ->where('notification_comments.user_id', $userId)
            ->count();

        // Lấy thêm thông tin chi tiết về hành vi (ví dụ: thời gian gần nhất)
        $recentComments = DB::table('notification_comments')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)  // Lấy 5 bình luận gần nhất
            ->get();

        return response()->json([
            'comments' => $comments,
            'notifications' => $notifications,
            'recent_comments' => $recentComments,
        ]);
    }


    public function getClasses()
    {
        // Trả về danh sách lớp học
        $classes = Classes::select('id', 'name')->get();

        return response()->json($classes);
    }

    public function getScoresAndAchievements($classId)
    {
        $students = Enrollment::where('class_id', $classId)
            ->pluck('student_id')
            ->toArray();

        $studentDetails = DB::table('users')->whereIn('id', $students)->get()->keyBy('id');

        $totalAssignments = ClassAssignment::where('class_id', $classId)->count();

        $results = [];
        $totalScores = 0;
        $totalStudents = 0;

        foreach ($students as $studentId) {
            $submissions = DB::table('class_submits')
                ->join('class_assignments', 'class_submits.class_assignment_id', '=', 'class_assignments.id')
                ->where('class_submits.student_id', $studentId)
                ->where('class_assignments.class_id', $classId)
                ->get();

            $averageScore = $submissions->avg('score');

            if ($averageScore !== null) {
                $totalScores += $averageScore;
                $totalStudents++;
            }

            $passedAssignments = $submissions->where('score', '>=', 50)->count();

            $achievements = [];
            if ($averageScore == 100) {
                $achievements[] = 'Xuất sắc: Điểm tối đa';
            }
            if ($submissions->count() == $totalAssignments) {
                $achievements[] = 'Hoàn thành tất cả bài tập';
            }

            $studentName = isset($studentDetails[$studentId]) ? $studentDetails[$studentId]->name : 'Chưa có tên';

            $results[] = [
                'student_name' => $studentName,
                'average_score' => round($averageScore, 2),
                'passed_assignments' => $passedAssignments,
                'total_assignments' => $totalAssignments,
                'achievements' => $achievements,
            ];
        }

        $classAverageScore = $totalStudents > 0 ? round($totalScores / $totalStudents, 2) : 0;

        $results['class_average_score'] = $classAverageScore;

        return response()->json($results);
    }
}
