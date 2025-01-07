<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentQuiz;
use App\Models\Choice;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Quiz;
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
            $assignments = AssignmentQuiz::all();
            $students = User::where('role_id', 3)->get();
            $questions = Quiz::all();
            $choices = Choice::all();
            $profile = User::findOrFail(Auth::id());
            return view('dashboard.index', compact(
                'assignments', 'students', 'questions', 'choices', 'profile'
            ));
        }

        Auth::logout();
        return redirect()
            ->route('admin.login')
            ->withErrors(['login' => 'Bạn không có quyền truy cập quản trị viên']);
    }
}
