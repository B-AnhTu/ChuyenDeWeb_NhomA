<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return view('adminPage');
    }
    // Cập nhật quyền hạn user
    public function updatePermissions(Request $request)
    {
        $roleHierarchy = [
            'user' => 1,
            'editor' => 2,
            'admin' => 3,
        ];

        $defaultPermissions = [
            'user' => 'viewer',
            'editor' => 'editor',
            'admin' => 'full_access',
        ];

        $request->validate([
            'role' => 'required|in:user,editor,admin',
            'user_id' => 'required|exists:users,user_id', // Kiểm tra ID người dùng
        ], [
            'role.required' => 'Vui lòng chọn vai trò người dùng',
            'role.in' => 'Vai trò không hợp lệ',
            'user_id.required' => 'ID người dùng là bắt buộc',
            'user_id.exists' => 'Người dùng không tồn tại',
        ]);

        $user = User::findOrFail($request->input('user_id')); // Lấy người dùng theo ID

        $currentUserRole = Auth::user()->role;
        $currentUserRoleLevel = $roleHierarchy[$currentUserRole];
        $targetUserRoleLevel = $roleHierarchy[$user->role];
        $newRoleLevel = $roleHierarchy[$request->input('role')];

        if ($currentUserRole == 'admin') {
            $user->role = $request->input('role');
            $user->permission = $defaultPermissions[$user->role];
        } elseif ($currentUserRole == 'editor') {
            // Kiểm tra xem nếu người dùng tự đổi quyền của bản thân là admin
            if ($user->role == 'admin') {
                return redirect()->route('admin.index')->with('error', 'Bạn không thể thay đổi quyền của quản trị viên.');
            }
            // Kiểm tra xem vai trò mới của người dùng có hợp lệ không
            if ($newRoleLevel <= $targetUserRoleLevel + 1 && $newRoleLevel <= $currentUserRoleLevel) {
                $user->role = $request->input('role');
                $user->permission = $defaultPermissions[$user->role];
            } else {
                return redirect()->route('admin.index')->with('error', 'Bạn không thể thay đổi vai trò của người dùng với quyền cao hơn bản thân.');
            }
        } else {
            return redirect()->route('admin.index')->with('error', 'Bạn không có quyền cập nhật quyền hạn của người dùng.');
        }

        $user->save();

        return redirect()->route('admin.index')->with('success', 'Cập nhật quyền người dùng thành công');
    }
}
