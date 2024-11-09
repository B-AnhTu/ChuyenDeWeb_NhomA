<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ProfileUserController extends Controller
{

    public function updateProfile(Request $request)
    {
        // Lấy người dùng hiện tại
        $user = Auth::user();

        // Định nghĩa các quy tắc xác thực
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:50|regex:/^[^\d\W_]+( [^\d\W_]+)*$/u',
            'email' => 'required|email|max:50|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'required|digits:10|starts_with:0',
            'address' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s]+$/|regex:/^(?!.*\s\s)/',
        ], [
            'fullname.required' => 'Họ và tên là bắt buộc',
            'fullname.regex' => 'Không đúng định dạng họ và tên',
            'fullname.max' => 'Họ và tên không quá 50 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Định dạng email không đúng',
            'email.max' => 'Email không dài quá 50 ký tự',
            'email.unique' => 'Email này đã tồn tại',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.digits' => 'Số điện thoại phải có 10 số',
            'phone.starts_with' => 'Số điện thoại phải bắt đầu bằng số 0',
            'address.max' => 'Địa chỉ không quá 255 ký tự',
            'address.regex' => 'Địa chỉ không được chứa ký tự đặc biệt hoặc khoảng trắng kép',
        ]);

        // Kiểm tra có lỗi hay không
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cập nhật thông tin người dùng
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->slug = Str::slug($request->fullname);
        $user->address = $request->address;

        // Lưu thay đổi
        $user->save();

        // Thông báo thành công
        return back()->with('success', 'Cập nhật thông tin thành công');
    }

    // update image
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profileImage' => 'required|mimes:jpeg,jpg,png,gif|max:5120', 
        ]);

        $user = auth()->user();

        if ($request->hasFile('profileImage')) {
            $file = $request->file('profileImage');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);

            // Xóa ảnh cũ nếu có
            if ($user->image && file_exists(public_path('img/profile-picture/' . $user->image))) {
                unlink(public_path('img/profile-picture/' . $user->image));
            }

            // Cập nhật ảnh mới trong database
            $user->image = $filename;
            $user->save();

            return response()->json(['success' => true, 'newImageUrl' => asset('img/profile-picture/' . $filename)]);
        }

        return response()->json(['success' => false]);
    }
}
