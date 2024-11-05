<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (Auth::check()) {
            // Kiểm tra quyền của người dùng
            if (in_array(Auth::user()->role, $roles)) {
                return $next($request); // Cho phép truy cập nếu có quyền
            } else {
                // Nếu quyền là user, hiển thị thông báo lỗi
                Session::flash('error', 'Bạn không đủ quyền truy cập trang quản trị');
                return redirect('/'); // Chuyển hướng về trang chủ
            }
        }

        return redirect('/login'); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    }
}
