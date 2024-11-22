<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLike extends Model
{
    use HasFactory;

    protected $table = 'product_like';
    protected $primaryKey = 'product_like_id';

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // Quan hệ với bảng User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với bảng Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Kiểm tra xem sản phẩm có được thích bởi người dùng
    public static function isLikedByUser($userId, $productId)
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    // Thêm sản phẩm vào danh sách yêu thích
    public static function addLike($userId, $productId)
    {
        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    // Xóa sản phẩm khỏi danh sách yêu thích
    public static function removeLike($existingLike)
    {
        return $existingLike->delete();
    }

    // Lấy danh sách sản phẩm yêu thích của người dùng
    public static function getUserLikedProducts($userId)
    {
        return self::where('user_id', $userId)
            ->with('product')
            ->get();
    }
}
