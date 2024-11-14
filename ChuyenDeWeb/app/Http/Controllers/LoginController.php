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
            'email.required' => 'Please enter your email',
            'email.email' => 'Invalid email format',
            'email.max' => 'Email cannot be longer than 50 characters',
            'password.required' => 'Please enter your password',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password cannot be longer than 20 characters',
        ]);

        // If validation fails, return to login page with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Attempt to log in the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Update the user's online status
            $user->is_online = true;
            $user->save();

            // Redirect based on user role (case-insensitive)
            if (strtolower($user->role) === 'admin') {
                return redirect()->route('orders.statistics');
            } else {
                return redirect()->route('index');
            }
        }

        // If login fails, return to login page with error
        return redirect()->back()->withErrors(['email' => 'Invalid email or password'])->withInput();
    }

    public function logout()
    {
        Log::info('User logging out');
        $user = Auth::user();

        // Update the user's online status
        if ($user) {
            $user->is_online = false;
            $user->save();
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
