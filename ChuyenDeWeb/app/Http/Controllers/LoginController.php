<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|max:20',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Định dạng email không hợp lệ',
            'email.max' => 'Email không được dài hơn 50 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu của bạn',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được dài hơn 20 ký tự',
        ]);

        // If validation fails, return to login page with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Attempt to log in the user using the method from User model
        $user = User::attemptLogin($request->email, $request->password);

        if ($user) {
            // Log in the user manually
            Auth::login($user);

            // Update the user's online status
            User::updateOnlineStatus($user->user_id, true);

            // Redirect based on user role (case-insensitive)
            if (strtolower($user->role) === 'admin') {
                return redirect()->route('orders.statistics');
            } else {
                return redirect()->route('index');
            }
        }

        // If login fails, return to login page with error
        return redirect()->back()->withErrors(['email' => 'Email hoặc mật khẩu không hợp lệ'])->withInput();
    }

    public function logout()
    {
        $user = Auth::user();

        // Update the user's online status
        if ($user) {
            User::updateOnlineStatus($user->user_id, false);
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
