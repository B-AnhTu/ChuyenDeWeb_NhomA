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
            'fullname' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => ['required', 'email', 'max:50', 'unique:users,email', new GmailOnly, new NoSpace],
            'password' => ['required', 'min:8', 'max:20', new NoSpace],
            'phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/', new NoSpecialCharacters, new NoSpace],
            'address' => ['required', 'string', 'max:255', new NoSpecialCharacters, new SingleSpaceOnly],
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

        // Tạo slug từ fullname
        $data['slug'] = $this->slugify($data['fullname']); // Sử dụng hàm slugify để tạo slug

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
            'slug' => $data['slug'],
            'address' => $data['address'],
        ]);

        return redirect()->route('userAdmin.index')->with('success', 'Thêm người dùng thành công');
    }

    /**
     * Hiển thị chi tiết user
     */
    public function show(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->first();
        if (!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        return view('userShow', compact('user'));
    }

    /**
     * Form cập nhật user
     */
    public function edit(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->first();
        if (!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        return view('userUpdate', compact('user'));
    }

    /**
     * Cập nhật user
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters],
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

        $user = User::where('slug', $slug)->first();

        
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
        // Tạo slug từ fullname
        $user->slug = $this->slugify($request->input('fullname')); // Sử dụng hàm slugify để tạo slug
        $user->updated_at = now();
        $user->save();

        return redirect()->route('userAdmin.index')->with('success', 'Cập nhật người dùng thành công');
    }

    /**
     * Xóa dữ liệu user
     */
    public function destroy($slug)
    {
        $user = User::where('slug', $slug)->first();

        // Kiểm tra nếu người dùng không tồn tại
        if(!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        $currentUser = auth()->user();

        // Kiểm tra quyền xóa dựa trên vai trò
        if ($currentUser->role === 'editor') {
            // Editor có thể xóa người dùng user và editor nhưng không được phép xóa người dùng admin
            if ($user->role === 'admin' || $user->role === 'editor' && $user->id !== $currentUser->id) {
                return redirect()->route('userAdmin.index')->with('error', 'Bạn không có quyền xóa người dùng này');
            }
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
    // Tìm kiếm người dùng theo tên (quan ly user)
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');

        // Tìm kiếm bằng Full Text
        $users = User::where('fullname','LIKE', '%' . $query . '%')->paginate(5);
        $totalUsers = User::count();
        $onlineUsers = User::where('is_online', true)->count();

        return view('userAdmin', compact('users', 'totalUsers', 'onlineUsers'));
    }
    // Tìm kiếm người dùng theo tên (quan ly user)
    public function searchPage(Request $request)
    {
        $query = $request->input('query');

        // Tìm kiếm bằng Full Text
        $users = User::where('fullname','LIKE', '%' . $query . '%')->paginate(5);

        return view('adminPage', compact('users'));
    }
    // Hàm để tạo slug
    private function slugify($text)
    {
        // Chuyển đổi ký tự có dấu thành không dấu
        $text = $this->removeVietnameseAccent($text);
        
        // Thay thế nhiều khoảng trắng thành một khoảng trắng
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text); // Xóa khoảng trắng ở đầu và cuối
        $text = strtolower($text); // Chuyển thành chữ thường
        $text = str_replace(' ', '-', $text); // Thay dấu khoảng trắng bằng dấu gạch nối

        return $text;
    }

    // Hàm để loại bỏ dấu tiếng Việt
    private function removeVietnameseAccent($string)
    {
        $unicode = [
            'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'đ' => 'd',
            'À' => 'A', 'Á' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A',
            'Ă' => 'A', 'Ằ' => 'A', 'Ắ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A',
            'Â' => 'A', 'Ầ' => 'A', 'Ấ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
            'È' => 'E', 'É' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E',
            'Ê' => 'E', 'Ề' => 'E', 'Ế' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I',
            'Ò' => 'O', 'Ó' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O',
            'Ô' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O',
            'Ơ' => 'O', 'Ờ' => 'O', 'Ớ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U',
            'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
            'Ỳ' => 'Y', 'Ý' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y',
            'Đ' => 'D',
        ];
        return strtr($string, $unicode);
    }
}
