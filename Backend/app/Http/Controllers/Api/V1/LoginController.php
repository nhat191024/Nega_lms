<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Vui lòng nhập tên đăng nhập hoặc email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$loginType => $request->login, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.'
            ], 401);
        }

        if (Auth::user()->role_id != 1) {
            Auth::logout();
            return response()->json([
                'message' => 'Bạn không có quyền truy cập quản trị viên.'
            ], 401);
        }

        return response()->json([
            'message' => 'Đăng nhập thành công.'
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Đăng xuất thành công.'
        ], 200);
    }
}
