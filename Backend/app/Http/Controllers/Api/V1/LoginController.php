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
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng.'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->status == 0) { // 0 là tài khoản bị khóa
            Auth::logout();
            return response()->json(['message' => 'Tài khoản của bạn đã bị khóa.'], 403);
        }

        if (!in_array($user->role_id, [1, 4])) {
            Auth::logout();
            return response()->json(['message' => 'Bạn không có quyền truy cập quản trị viên.'], 403);
        }

        if ($user->tokens()->count() > 0) {
            $user->tokens()->delete();
        }

        $token = $user->createToken('authToken', ["*"])->plainTextToken;

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
        /** @var \App\Models\User $user */
        $user = Auth::user();
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
