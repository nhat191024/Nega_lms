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
                'login' => 'required|string',
                'password' => 'required|string|min:6',
            ],
            [
                'login.required' => 'Vui lòng nhập tên đăng nhập hoặc email.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            ],
        );

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$loginType => $request->login, 'password' => $request->password])) {
            return back()
                ->withErrors([
                    'login' => 'Tên đăng nhập hoặc mật khẩu không đúng.',
                    'password' => 'Tên đăng nhập hoặc mật khẩu không đúng.',
                ])
                ->withInput();
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->status == 0) { // 0 là tài khoản bị khóa
            Auth::logout();
            return back()
                ->withErrors(['login' => 'Tài khoản của bạn đã bị khóa.'])
                ->withInput();
        }

        if ($user->role_id != 1) {
            Auth::logout();
            return back()
                ->withErrors(['login' => 'Bạn không có quyền truy cập quản trị viên.'])
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
