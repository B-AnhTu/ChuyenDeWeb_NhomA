<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileUserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->withErrors(['error' => 'Không tìm thấy thông tin người dùng'])->withInput();
        }

        // Định nghĩa các quy tắc xác thực
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:50|regex:/^[^\d\W_]+( [^\d\W_]+)*$/u',
            'email' => 'required|email|max:50|unique:users,email,' . $user->user_id . ',user_id|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone' => 'required|digits:10|starts_with:0',
            'address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s]+$/|regex:/^(?!.*\s\s)/',
        ], [
            'fullname.required' => 'Họ và tên là bắt buộc',
            'fullname.regex' => 'Không đúng định dạng họ và tên',
            'fullname.max' => 'Họ và tên không quá 50 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Định dạng email không đúng',
            'email.max' => 'Email không dài quá 50 ký tự',
            'email.regex' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã tồn tại',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.digits' => 'Số điện thoại phải có 10 số',
            'phone.starts_with' => 'Số điện thoại phải bắt đầu bằng số 0',
            'address.max' => 'Địa chỉ không quá 255 ký tự',
            'address.regex' => 'Địa chỉ không được chứa ký tự đặc biệt hoặc khoảng trắng kép',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Gọi model để cập nhật thông tin
        $user->updateProfileInfo($request->only(['fullname', 'email', 'phone', 'address']));

        return back()->with('success', 'Cập nhật thông tin thành công');
    }

    public function updateProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profileImage' => 'required|mimes:jpeg,jpg,png,gif|max:5120',
        ], [
            'profileImage.required' => 'Vui lòng chọn một file ảnh.',
            'profileImage.mimes' => 'Không đúng định dạng ảnh. Chỉ chấp nhận jpeg, jpg, png, gif.',
            'profileImage.max' => 'Kích thước ảnh không được vượt quá 5MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('profileImage'),
            ]);
        }

        $user = Auth::user();

        if ($request->hasFile('profileImage')) {
            $file = $request->file('profileImage');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);

            // Gọi model để cập nhật ảnh
            $user->updateProfileImage($filename);

            return response()->json([
                'success' => true,
                'newImageUrl' => asset('img/profile-picture/' . $filename),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi khi tải ảnh.',
        ]);
    }
}
