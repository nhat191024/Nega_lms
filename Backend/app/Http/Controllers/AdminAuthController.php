<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
            ],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            ],
        );

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng.',
                    'password' => 'Email hoặc mật khẩu không đúng.',
                ])
                ->withInput();
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->status == 0) {
            Auth::logout();
            return back()
                ->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.'])
                ->withInput();
        }

        if (!in_array($user->role_id, [1, 4])) {
            Auth::logout();
            return back()
                ->withErrors(['email' => 'Bạn không có quyền truy cập quản trị viên.'])
                ->withInput();
        }

        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
