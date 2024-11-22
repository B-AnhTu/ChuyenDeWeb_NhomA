<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\SlugService;

class RegisterController extends Controller 
{
    protected $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8|max:20',
            'fullname' => ['required', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'phone' => ['required', 'regex:/^0[3|5|7|8|9][0-9]{8}$/'],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Sai định dạng email',
            'email.max' => 'Email không dài quá 50 ký tự',
            'email.unique' => 'Email này đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không quá 20 ký tự',
            'fullname.required' => 'Vui lòng nhập họ và tên',
            'fullname.regex' => 'Họ và tên không có số, ký tự đặc biệt',
            'fullname.max' => 'Họ và tên không được quá 50 ký tự',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại phải là số hợp lệ tại Việt Nam',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Đăng ký thành công, lưu user vào database thông qua model
        User::registerUser(
            $request->email,
            $request->password,
            $request->fullname,
            $request->phone
        );

        // Thêm thông báo thành công vào session
        session()->flash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');

        // Chuyển hướng đến trang đăng nhập
        return redirect('/login');
    }
}