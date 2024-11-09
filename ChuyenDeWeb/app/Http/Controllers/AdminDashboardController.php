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
    public function updatePermissions(Request $request, $id)
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
        ],[
            'role.required' => 'Vui lòng chọn vai trò người dùng',
            'role.in' => 'Vai trò không hợp lệ',
        ]);

        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Người dùng không tồn tại']);
        }

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
                return response()->json(['success' => false, 'message' => 'Bạn không thể thay đổi quyền của quản trị viên.']);
            }
            // Kiểm tra xem vai trò mới của người dùng có hợp lệ không
            if ($newRoleLevel <= $targetUserRoleLevel + 1 && $newRoleLevel <= $currentUserRoleLevel) {
                $user->role = $request->input('role');
                $user->permission = $defaultPermissions[$user->role];
            } else {
                return response()->json(['success' => false, 'message' => 'Bạn không thể thay đổi vai trò của người dùng có quyền cao hơn bản thân.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền cập nhật quyền hạn của người dùng.']);
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật quyền người dùng thành công']);
    }
}
