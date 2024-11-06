<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function update(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
        ]);

        $user = Auth::user();
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }
    // Cập nhật hình ảnh trang profile
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'profileImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $imageName = time() . '.' . $request->profileImage->extension();
        $request->profileImage->move(public_path('img/profile-picture'), $imageName);

        // Xóa ảnh cũ nếu có
        if ($user->image) {
            $oldImagePath = public_path('img/profile-picture/' . $user->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $user->image = $imageName;
        $user->save();

        return response()->json(['success' => true, 'newImageUrl' => asset('img/profile-picture/' . $imageName)]);
    }
}
