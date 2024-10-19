<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Kiểm tra các yêu cầu
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|max:20',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Sai định dạng email',
            'email.max' => 'Email không dài quá 50 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không quá 20 ký tự',
        ]);

        // Nếu có lỗi, trả về trang đăng nhập với thông báo lỗi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Cập nhật trạng thái is_online khi người dùng đăng nhập
            $user = Auth::user();
            $user->is_online = true;
            $user->save();

            // Chuyển hướng đến trang chủ
            return redirect()->route('index');
        }

        // Nếu đăng nhập không thành công, trả về với thông báo lỗi
        return redirect()->back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng'])->withInput();
    }

    public function logout()
    {
        Log::info('User logging out');
        $user = Auth::user();
        if ($user) {
            $user->is_online = false;
            $user->save();
        }

        Auth::logout();

        // Dọn dẹp phiên
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
