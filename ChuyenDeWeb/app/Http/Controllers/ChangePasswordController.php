<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        // Kiểm tra các yêu cầu
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|max:20|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.max' => 'Mật khẩu mới không được quá 20 ký tự.',
            'new_password.different' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại.',
            'new_password_confirmation.required' => 'Vui lòng nhập lại mật khẩu mới.',
            'new_password_confirmation.same' => 'Nhập lại mật khẩu mới không khớp.',
        ]);

        // Nếu có lỗi, trả về trang đổi mật khẩu với thông báo lỗi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

        // Cập nhật mật khẩu mới
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Thông báo thay đổi mật khẩu thành công
        Session::flash('success', 'Thay đổi mật khẩu thành công!');
        return redirect()->route('change-password');
    }
}
