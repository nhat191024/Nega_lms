<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status == 0) { // 0 là tài khoản bị khóa 
                Auth::logout();
                return redirect('/login')->withErrors(['account_locked' => 'Tài khoản của bạn đã bị khóa.']);
            }
        }
        return $next($request);
    }
}
