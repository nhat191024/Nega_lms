<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLoginForm(){
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Kiểm tra xem giá trị nhập vào là email hay username
        $user = User::where('email', $request->login)->orWhere('username', $request->login)->first();

        // Nếu tìm thấy người dùng và mật khẩu khớp
        if ($user && Auth::attempt(['email' => $request->login, 'password' => $request->password]) || 
            ($user && Auth::attempt(['username' => $request->login, 'password' => $request->password]))) {
            return redirect()->intended('master');
        } else {
            return back()->withErrors(['login' => 'Invalid credentials.'])->withInput();
        }
    }
}
