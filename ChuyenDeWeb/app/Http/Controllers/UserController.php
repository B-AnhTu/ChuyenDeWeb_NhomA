<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\SingleSpaceOnly;
use App\Rules\GmailOnly;
use App\Rules\NoSpace;
use App\Services\User\UserService;
use App\Services\User\UserSortAndSearch;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserRoleRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    protected $userService, $userSortAndSearch; // Khai báo thuộc tính slugService

    // Constructor
    public function __construct(UserService $userService, UserSortAndSearch $userSortAndSearch) 
    {
        $this->userService = $userService;
        $this->userSortAndSearch = $userSortAndSearch;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm và lựa chọn sắp xếp từ request
        $searchTerm = $request->input('query');
        $sortBy = $request->input('sort_by');

        // Khởi tạo truy vấn
        $query = User::query(); // Tạo một truy vấn mới

        // Nếu có tìm kiếm, thực hiện tìm kiếm
        if ($searchTerm) {
            $query = $this->userSortAndSearch->searchUsers($searchTerm);
        }

        // Nếu có sắp xếp, thực hiện sắp xếp
        if ($sortBy) {
            $query = $this->userSortAndSearch->sortUsers($query, $sortBy); // Gọi phương thức sắp xếp từ service
        }

        else {
            // Nếu không có sắp xếp, sắp xếp theo ngày tạo mới
            $query = $query->orderBy('created_at', 'desc');
        }

        $totalUsers = $this->userService->getTotalUsers();
        $onlineUsers = $this->userService->getOnlineUsers(); 

        // Phân trang danh mục
        $users = $query->paginate(5);

        return view('userAdmin', [
            'users' => $users, 
            'totalUsers' => $totalUsers,
            'onlineUsers' => $onlineUsers,
            'filters' => [
                'searchTerm' => $searchTerm,
                'sort_by' => $sortBy,
            ]

        ]);

        //return view('userAdmin', compact('users', 'totalUsers', 'onlineUsers'));
    }

    public function list()
    {
        
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
    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('userAdmin.index')->with('success', 'Thêm người dùng thành công');
    }

    /**
     * Hiển thị chi tiết user
     */
    public function show(Request $request, $slug)
    {
        $user = $this->userService->getUserBySlug($slug);
        if (!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        return view('userShow', compact('user'));
    }

    /**
     * Form cập nhật user
     */
    public function edit($slug)
    {
        $user = $this->userService->getUserBySlug($slug);
        if (!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại');
        }
        return view('userUpdate', compact('user'));
    }

    /**
     * Cập nhật user
     */
    public function update(UpdateUserRequest $request, $slug)
    {
        try {
            // Tìm user theo slug
            $user = $this->userService->getUserBySlug($slug);

            // Lưu dữ liệu đã validated từ request
            $validatedData = $request->validated();

            // Gọi service để cập nhật user
            $this->userService->updateUser($user, $validatedData);

            // Thông báo thành công
            //Session::flash('success', 'Product updated successfully.');
            return redirect()->route('userAdmin.index')->with('success', 'Cập nhật người dùng thành công');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('userAdmin.index')->withInput(); // Chuyển hướng về trang cập nhật
        }
    }

    /**
     * Xóa dữ liệu user
     */
    public function destroy($slug)
    {
        $user = $this->userService->getUserBySlug($slug);
        if (!$user) {
            return redirect()->route('userAdmin.index')->with('error', 'Người dùng không tồn tại.');
        }

        // Gọi service để xóa người dùng
        try {
            $this->userService->deleteUserBySlug($slug, Auth::user());
            return redirect()->route('userAdmin.index')->with('success', 'Xóa người dùng thành công.');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('userAdmin.index')->withInput();
        }
        
    }

    //Hiển thị danh sách user trên trang quản lý vai trò
    public function listAdmin(Request $request)
    {
        // Lấy từ khóa tìm kiếm và lựa chọn sắp xếp từ request
        $searchTerm = $request->input('query');
        $sortBy = $request->input('sort_by');

        // Khởi tạo truy vấn
        $query = User::query(); // Tạo một truy vấn mới

        // Nếu có tìm kiếm, thực hiện tìm kiếm
        if ($searchTerm) {
            $query = $this->userSortAndSearch->searchUsers($searchTerm);
        }

        // Nếu có sắp xếp, thực hiện sắp xếp
        if ($sortBy) {
            $query = $this->userSortAndSearch->sortUsers($query, $sortBy); // Gọi phương thức sắp xếp từ service
        }

        // Phân trang danh mục
        $users = $query->paginate(5);

        return view('adminPage', [
            'users' => $users, 
            'filters' => [
                'searchTerm' => $searchTerm,
                'sort_by' => $sortBy,
            ]
        ]);
    }
    /**
     * Cập nhật quyền người dùng
     */
    public function updatePermissions(UserRoleRequest $request, $slug)
    {
        $user = $this->userService->getUserBySlug($slug);
        if (!$user) {
            return redirect()->route('admin.index')->with('error', 'Người dùng không tồn tại.');
        }

        // Lấy vai trò mới từ request
        $newRole = $request->validated()['role']; // Lấy 'role' từ validated data

        // Gọi service để cập nhật quyền
        try {
            $this->userService->updatePermissions($user, $newRole);
            return redirect()->route('admin.index')->with('success', 'Cập nhật quyền người dùng thành công.');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('admin.index')->withInput();
        }
    }
}
