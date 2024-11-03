<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\SingleSpaceOnly;
use App\Rules\GmailOnly;
use App\Rules\NoSpace;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function list()
    {
        $users = User::paginate(5);
        $totalUsers = User::count();
        $onlineUsers = User::where('is_online', true)->count();

        return view('userAdmin', compact('users', 'totalUsers', 'onlineUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('userCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:50', new SingleSpaceOnly],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => ['required', 'email', 'max:50', 'unique:users,email', new GmailOnly, new NoSpace],
            'password' => ['required', 'min:8', 'max:20', new NoSpace],
            'phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/', new NoSpecialCharacters, new NoSpace],
            'address' => ['required', 'string', 'max:255', new NoSpecialCharacters],
        ],[
            'fullname.required' => 'Vui lòng nhập tên người dùng',
            'image.required' => 'Vui lòng nhập ảnh',
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'email.max' => 'Email không được quá 50 ký tự',
            'phone.digits' => 'Số điện thoại phải có 10 chữ số',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);

            // Cập nhật ảnh mới trong database
            $data['image'] = $filename;
        }

        User::create([
            'fullname' => $data['fullname'],
            'image' => $data['image'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

        return redirect()->route('userAdmin.index')->with('success', 'Thêm người dùng thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $user_id)
    {
        $user = User::findOrFail($user_id);
        if (!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        return view('userShow', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $user_id)
    {
        $user = User::findOrFail($user_id);
        return view('userUpdate', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:50', new SingleSpaceOnly],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => ['required', 'email', 'max:50', 'unique:users,email', new GmailOnly, new NoSpace],
            'password' => 'required|min:8|max:20',
            'phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/', new NoSpecialCharacters, new NoSpace],
            'address' => ['required', 'string', 'max:255', new NoSpecialCharacters],
        ],[
            'fullname.required' => 'Vui lòng nhập tên người dùng',
            'image.required' => 'Vui lòng nhập ảnh',
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'email.max' => 'Email không được quá 50 ký tự',
            'phone.digits' => 'Số điện thoại phải có 10 chữ số',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
        ]);

        $user = User::findOrFail($id);

        if(!$user){
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);

            // Delete old image if exists
            if ($user->image && file_exists(public_path('img/profile-picture/' . $user->image))) {
                unlink(public_path('img/profile-picture/' . $user->image));
            }

            // Cập nhật ảnh mới trong database
            $user->image = $filename;
        }

        $user->fullname = $request->input('fullname');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();

        return redirect()->route('userAdmin.index')->with('success', 'Cập nhật người dùng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if(!$user){
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        // Delete image if exists
        if ($user->image && file_exists(public_path('img/profile-picture/' . $user->image))) {
            unlink(public_path('img/profile-picture/' . $user->image));
        }
        try {
            $user->delete();
            return redirect()->route('userAdmin.index')->with('success', 'Xóa người dùng thành công');
        } catch (\Exception $e) {
            return redirect()->route('userAdmin.index')->with('error', 'Xóa người dùng không thành công');
        }
    }

    //Hiển thị danh sách user trên trang quản lý vai trò
    public function listRole()
    {
        $users = User::paginate(5);
        return view('adminPage', compact('users'));
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
                return response()->json(['success' => false, 'message' => 'Bạn không thể thay đổi cài đặt hệ thống.']);
            }
            // Kiểm tra xem vai trò mới của người dùng có hợp lệ không
            if ($newRoleLevel <= $targetUserRoleLevel + 1 && $newRoleLevel <= $currentUserRoleLevel) {
                $user->role = $request->input('role');
                $user->permission = $defaultPermissions[$user->role];
            } else {
                return response()->json(['success' => false, 'message' => 'Bạn không thể thay đổi quyền của người dùng có cấp độ quyền cao hơn bạn.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền cập nhật quyền hạn của người dùng.']);
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật quyền người dùng thành công']);
    }
    // Sắp xếp theo tên, quyền, ngày cập nhật (quan ly quyen)
    public function sortAdmin(Request $request)
    {
        $query = User::query();

        // Sắp xếp theo yêu cầu
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('fullname', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('fullname', 'desc');
                    break;
                case 'role_asc':
                    $query->orderByRaw("FIELD(role, 'user', 'editor', 'admin') ASC");
                    break;
                case 'role_desc':
                    $query->orderByRaw("FIELD(role, 'user', 'editor', 'admin') DESC");
                    break;
                case 'updated_at_asc':
                    $query->orderBy('updated_at', 'asc');
                    break;
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                default:
                    // Mặc định không sắp xếp
                    break;
            }
        }

        $users = $query->paginate(5); // Phân trang

        return view('adminPage', compact('users'));
    }
    // Sắp xếp theo tên, ngày cập nhật (quan ly user)
    public function sortUsers(Request $request)
    {
        $query = User::query();

        // Sắp xếp theo yêu cầu
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('fullname', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('fullname', 'desc');
                    break;
                case 'updated_at_asc':
                    $query->orderBy('updated_at', 'asc');
                    break;
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                default:
                    // Mặc định không sắp xếp
                    break;
            }
        }

        $users = $query->paginate(5); // Phân trang

        return view('userAdmin', compact('users'));
    }
}
