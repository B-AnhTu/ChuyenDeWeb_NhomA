<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

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
        return $this->hasOne(Blog::class);
    }

    // Quan hệ với bảng BlogComment
    public function blogcomments()
    {
        return $this->hasMany(BlogComment::class);
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
