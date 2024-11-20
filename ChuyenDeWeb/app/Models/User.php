<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\SlugService;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'phone',
        'is_online',
        'address', 
        'image',
        'slug'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

    // Quan hệ với bảng Blog
    public function blog()
    {
        return $this->hasOne(Blog::class, 'user_id');
    }

    // Quan hệ với bảng BlogComment
    public function blogcomments()
    {
        return $this->hasMany(BlogComment::class, 'user_id');
    }

    // Quan hệ với bảng Cart
    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    // Quan hệ với bảng ProductLike
    public function productLikes()
    {
        return $this->hasMany(ProductLike::class, 'user_id');
    }

    // Hàm kiểm tra khởi tạo và cập nhật slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->slug = static::generateUniqueSlug($user->fullname, $user->user_id);
        });

        static::updating(function ($user) {
            $user->slug = static::generateUniqueSlug($user->fullname, $user->user_id);
        });
    }

    // Tạo slug không trùng lặp
    protected static function generateUniqueSlug($fullname, $userId = null)
    {
        // Tạo slug từ fullname
        $slug = SlugService::slugify($fullname);
        $secretKey = 'khongthehacktdc2004'; // Thay thế bằng chuỗi ký tự bí mật của bạn
        $encodedId = base64_encode($userId . $secretKey); // Mã hóa ID người dùng

        // Tạo slug duy nhất bằng cách thêm ID đã mã hóa vào cuối slug
        $uniqueSlug = $slug . '-' . $encodedId;

        return $uniqueSlug; // Trả về slug duy nhất
    }

    //Hàm chức năng thêm xóa sửa
    /**
     * Lấy danh sách người dùng
     */
    public static function getAllUsers()
    {
        return self::all();
    }
    /**
     * Lấy danh sách user online
     */
    public static function getOnlineUsers(){
        return self::where('is_online', true)->count();
    }
    /**
     * Lấy user theo slug
     */ 
    public static function getUserBySlug($slug)
    {
        // Lấy user theo slug gốc
        $user = static::where('slug', $slug)->first();

        if ($user) {
            return $user;
        }

        return null; // Nếu không tìm thấy
    }
    /**
     * Thêm user
     */
    // Phương thức tạo người dùng
    public static function createUser($data)
    {
        return self::create($data);
    }

    // Phương thức cập nhật người dùng
    public function updateUser($data)
    {
        return $this->update($data);
    }
    /**
     * Xóa user
     */
    public static function deleteUserBySlug($slug, $currentUser){
        $user = User::getUserBySlug($slug);

        // Kiểm tra nếu người dùng không tồn tại
        if (!$user) {
            throw new \Exception('Người dùng không tồn tại');
        }

        // Kiểm tra quyền xóa dựa trên vai trò
        if ($currentUser->role === 'editor') {
            // Editor có thể xóa người dùng user và editor nhưng không được phép xóa người dùng admin
            if ($user->role === 'admin' || ($user->role === 'editor' && $user->id !== $currentUser->id)) {
                throw new \Exception('Bạn không có quyền xóa người dùng này');
            }
        }

        // Xóa ảnh nếu có
        if ($user->image && file_exists(public_path('img/profile-picture/' . $user->image))) {
            unlink(public_path('img/profile-picture/' . $user->image));
        }

        // Thực hiện xóa người dùng
        $user->delete();
        return true; // Trả về true nếu xóa thành công
    }
    /**
     * Tìm kiếm
    */
    public static function search($searchTerm)
    {
        if ($searchTerm) {
            return self::where('fullname', 'like', '%' . $searchTerm . '%');
        }
        return self::all();
    }
    /**
     * Sắp xếp
     */
    public static function sort($query, $sortBy)
    {
        switch ($sortBy) {
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
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'asc');
                break;
        }
        return $query;
    }
    /**
     * Cập nhật quyền cho người dùng
     */
    public function updatePermissions($newRole)
    {
        $roleHierarchy = [
            'user' => 1,
            'editor' => 2,
            'admin' => 3,
        ];

        $currentUserRole = Auth::user()->role; // Lấy vai trò của người dùng hiện tại
        $currentUserRoleLevel = $roleHierarchy[$currentUserRole];
        $targetUserRoleLevel = $roleHierarchy[$this->role];
        $newRoleLevel = $roleHierarchy[$newRole];

        // Nếu người dùng hiện tại là admin, cho phép cập nhật quyền của người khác
        if ($currentUserRole == 'admin') {
            $this->role = $newRole;
            $this->save();
            return true; // Trả về true nếu cập nhật thành công
        } elseif ($currentUserRole == 'editor') {
            // Kiểm tra xem có phải tự thay đổi quyền thành admin không
            if ($this->role == 'admin') {
                throw new \Exception('Bạn không thể thay đổi quyền của quản trị viên.');
            }
            // Kiểm tra xem có thể thay đổi lên quyền cao hơn hay không
            if ($newRoleLevel <= $targetUserRoleLevel + 1 && $newRoleLevel <= $currentUserRoleLevel) {
                $this->role = $newRole;
                $this->save();
                return true;
            } else {
                throw new \Exception('Bạn không thể thay đổi vai trò của người dùng có quyền cao hơn bản thân.');
            }
        } else {
            throw new \Exception('Bạn không có quyền cập nhật quyền hạn của người dùng.');
        }
    }
    public static function getUserIdBySlug($slug){
        $user = User::getUserBySlug($slug);
        if($user){
            return $user->user_id;
        }
        return null;
    }
    
    
}
