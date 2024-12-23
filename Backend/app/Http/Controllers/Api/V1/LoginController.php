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

        /** @var \App\Models\User $user **/  $user = Auth::user();
        if ($user->tokens()->count() > 0) {
            $user->tokens()->delete();
        }

        if ($user->role_id == 1) {
            $token = $user->createToken('authToken', ["*"])->plainTextToken;
        } else {
            $token = $user->createToken('authToken', [$user->role->name])->plainTextToken;
        }

        return response()->json([
            'message' => 'Đăng nhập thành công.',
            'username' => $user->username,
            'avatar' => asset($user->avatar),
            'role' => $user->role->name,
            'token' => $token
        ], 200);
    }

    public function logout()
    {
        /** @var \App\Models\User $user **/  $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công.'
        ], 200);
    }

    public function tokenCheck()
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'Token hợp lệ.'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Token không hợp lệ.'
            ], 401);
        }
    }
}
