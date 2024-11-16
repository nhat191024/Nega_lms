<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

        public function login(Request $request)
        {
            $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $user = User::where('email', $request->login)
                        ->orWhere('username', $request->login)
                        ->first();
    
            if ($user) {
                if (Auth::attempt(['email' => $request->login, 'password' => $request->password]) || 
                    Auth::attempt(['username' => $request->login, 'password' => $request->password])) {
    
                    if (Auth::user()->role_id == 1) {
                        return redirect()->route('master');
                    } else {
                        Auth::logout();
                        return back()->withErrors(['login' => 'Bạn không có quyền truy cập quản trị viên'])->withInput();
                    }
                } else {
                    return back()->withErrors(['login' => 'Thông tin không hợp lệ, vui lòng nhập lại'])->withInput();
                }
            } else {
                return back()->withErrors(['login' => 'Người dùng không tìm thấy'])->withInput();
            }
        }
    
        public function showMaster()
        {
            if (!Auth::check()) {
                return redirect()->route('admin.login');
            }
            if (Auth::user()->role_id != 1) {
                return redirect()->route('admin.login')->withErrors(['login' => 'You do not have admin access.']);
            }
            return view('master');
        }
}
