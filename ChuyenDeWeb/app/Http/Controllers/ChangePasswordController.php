<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        // Lấy người dùng hiện tại
        $user = Auth::user();

        // Kiểm tra nếu người dùng không đăng nhập
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Bạn cần đăng nhập để thực hiện thao tác này.']);
        }

        // Xác thực dữ liệu đầu vào
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

        // Xử lý nếu có lỗi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

        // Cập nhật mật khẩu mới qua phương thức trong Model
        $user->updatePassword($request->new_password);

        // Tạo thông báo thành công
        Session::flash('success', 'Thay đổi mật khẩu thành công!');
        return redirect()->route('change-password');
    }
}
