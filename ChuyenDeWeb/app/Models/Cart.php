<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'cart_id';

    protected $fillable = [
        'user_id',
    ];

    // Quan hệ với bảng CartProduct
    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }

    // Quan hệ với bảng User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getUserCartWithProducts($userId)
    {
        return self::where('user_id', $userId)
            ->with('cartProducts.product')
            ->first();
    }

    public function calculateTotal()
    {
        return $this->cartProducts->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });
    }

    public function clearCart()
    {
        $this->cartProducts()->delete();
        $this->delete();
    }
}
