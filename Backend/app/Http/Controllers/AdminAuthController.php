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
                'login' => 'required|string|email',
                'password' => 'required|string|min:6',
            ],
            [
                'login.required' => 'Vui lòng nhập email.',
                'login.email' => 'Email không hợp lệ.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            ]
        );

        $credentials = ['email' => $request->login, 'password' => $request->password];

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'login' => 'Email hoặc mật khẩu không đúng.',
                ])
                ->withInput();
        }

        $user = Auth::user();

        if ($user->status == 'closed') {
            Auth::logout();
            return back()
                ->withErrors(['login' => 'Tài khoản của bạn đã bị khóa.'])
                ->withInput();
        }

        if (!in_array($user->role_id, [1, 4])) {
            Auth::logout();
            return back()
                ->withErrors(['login' => 'Bạn không có quyền truy cập quản trị viên.'])
                ->withInput();
        }

        return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công!');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}
