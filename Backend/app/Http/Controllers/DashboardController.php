<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            $submissions = Submission::with(['assignment', 'student'])
                ->whereHas('student', function ($query) {
                    $query->where('role_id', 3);
                })
                ->whereNotNull('created_at')
                ->orderBy('total_score', 'desc')
                ->get();

            // Tính điểm trung bình cho mỗi bài quiz
            $averageScoreForAssignments = $this->getAverageScoreForAssignments($submissions);

            // Lấy số người tham gia các quiz theo thời gian
            $participationData = $this->getParticipationData($submissions);

            return view('dashboard.index', [
                'students' => User::with('enrollments.class')->where('role_id', 3)->orderBy('created_at', 'desc')->get(),
                'teachers' => User::where('role_id', 2)->get(),
                'classes' => Classes::all(),
                'submissions' => $submissions,
                'enrollments' => Enrollment::all(),
                // 'assignments' => Assignment::all(),
                'averageScoreForAssignments' => $averageScoreForAssignments,
                'dates' => $participationData['dates'],
                'participantsCount' => $participationData['participantsCount'],
            ]);
        }

        Auth::logout();
        return redirect()
            ->route('admin.login')
            ->withErrors(['login' => 'Bạn không có quyền truy cập quản trị viên']);
    }

    private function getAverageScoreForAssignments($submissions)
    {
        return $submissions->groupBy('assignment_id')->map(function ($submissionsByAssignment) {
            $totalScore = $submissionsByAssignment->sum('total_score');
            $totalSubmissions = $submissionsByAssignment->count();
            $averageScore = $totalSubmissions > 0 ? $totalScore / $totalSubmissions : 0;

            return [
                'assignment_name' => $submissionsByAssignment->first()->assignment->name,
                'total_score' => $totalScore,
                'average_score' => $averageScore,
            ];
        });
    }

    private function getParticipationData($submissions)
    {
        // Nhóm các bài nộp theo ngày
        $groupedByDate = $submissions->groupBy(function ($submission) {
            return Carbon::parse($submission->created_at)->toDateString(); // Nhóm theo ngày
        });

        // Lấy danh sách ngày và số người tham gia tương ứng
        $dates = $groupedByDate->keys();
        $participantsCount = $groupedByDate->map(function ($submissions) {
            return $submissions->unique('student_id')->count();
        });

        return [
            'dates' => $dates,
            'participantsCount' => $participantsCount,
        ];
    }

    public function getCompletionRate($assignmentId)
    {
        // Lấy bài quiz từ bảng assignments
        $assignment = Assignment::findOrFail($assignmentId);

        // Lấy số lượng học sinh đã hoàn thành bài quiz (tức là đã có submission)
        $completedCount = Submission::where('assignment_id', $assignmentId)->distinct('student_id')->count('student_id'); // Đếm số học sinh duy nhất nộp bài

        // Lấy tổng số học sinh đã đăng ký lớp học có bài quiz
        $classId = $assignment->class_id;
        $totalStudents = Enrollment::where('class_id', $classId)->count('student_id'); // Đếm tổng số học sinh trong lớp

        // Tính tỷ lệ người hoàn thành
        $completionRate = 0;
        if ($totalStudents > 0) {
            $completionRate = ($completedCount / $totalStudents) * 100;
        }

        // Trả về tỷ lệ người hoàn thành
        return response()->json([
            'assignment_id' => $assignmentId,
            'completed_count' => $completedCount,
            'total_students' => $totalStudents,
            'completion_rate' => number_format($completionRate, 2) . '%',
        ]);
    }
}
