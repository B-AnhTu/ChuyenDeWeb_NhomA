<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->slug = static::generateUniqueSlug($user->fullname);
        });

        static::updating(function ($user) {
            $user->slug = static::generateUniqueSlug($user->fullname, $user->user_id);
        });
    }

    // Tạo slug không trùng lặp
    protected static function generateUniqueSlug($fullname, $userId = null)
    {
        $slug = Str::slug($fullname);
        $originalSlug = $slug;
        $counter = 1;

        // Kiểm tra trùng lặp
        // Chỉ cần kiểm tra nếu có user_id (trong trường hợp là update)
        while (static::where('slug', $slug)
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', '<>', $userId);
                })->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // Nếu userId không null, thêm userId vào sau slug
        if ($userId) {
            $slug = $slug . '-' . $userId;
        }

        return $slug;
    }

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
    
}
