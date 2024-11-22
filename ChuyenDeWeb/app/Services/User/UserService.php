<?php
namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\SlugService;


class UserService
{
    protected $slugService; // Khai báo thuộc tính

    // Constructor để nhận SlugService thông qua dependency injection
    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService; // Khởi tạo thuộc tính slugService
    }
    // Lấy danh sách người dùng, người dùng online và tổng số người dùng
    public function getAllUsers()
    {
        return User::getAllUsers();
    }
    public function getTotalUsers(){
        return User::count();
    }
    public function getOnlineUsers(){
        return User::getOnlineUsers();
    }
    public function getUserById($id){
        return User::getUserById($id); 
    }
    /**
     * Lấy user theo slug
     */
    public function getUserBySlug($slug){
        $userId = User::decodeSlug($slug); 
        return self::getUserById($userId); 
    }
    /**
     * Cập nhật quyền cho người dùng
     */
    public function updatePermissions(User $user, $newRole)
    {
        return $user->updatePermissions($newRole);
    }
    public function createUser($validatedData)
    {
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);
            $validatedData['image'] = $filename;
        }

        // Tạo người dùng mới
        $user = User::createUser($validatedData);

        // Tạo slug cho người dùng sau khi đã tạo
        $user->slug = User::generateUniqueSlug($user->fullname, $user->user_id);
        $user->save();

        return $user; // Trả về người dùng đã tạo
    }

    public function updateUser($user, $validatedData)
    {
        // Cập nhật thông tin người dùng
        $user->updateUser($validatedData);

        // Nếu fullname đã thay đổi, tạo lại slug
        if ($user->fullname !== $validatedData['fullname']) {
            $validatedData['slug'] = User::generateUniqueSlug($validatedData['fullname'], $validatedData['user_id']); // Sử dụng fullname mới từ validatedData
        }

        $user->save();

        return $user; // Trả về người dùng đã cập nhật
    }
    //Hàm tạo slug
    private function generateSlug($fullname, $userId = null): string
    {
        // Tạo slug từ fullname
        $slug = SlugService::slugify($fullname);
        
        $encodedId = base64_encode($userId); // Mã hóa ID người dùng

        // Tạo slug duy nhất bằng cách thêm ID đã mã hóa vào cuối slug
        $uniqueSlug = $slug . '-' . $encodedId;

        return $uniqueSlug; // Trả về slug duy nhất
    }
    /**
     * Xóa người dùng theo slug với kiểm tra quyền
     */
    public function deleteUserBySlug($slug, $currentUser)
    {
        return User::deleteUserBySlug($slug, $currentUser);
    }
}
