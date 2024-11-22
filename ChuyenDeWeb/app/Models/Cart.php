<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'cart_id';

    protected $fillable = [
        'user_id',
    ];

    /**
     * Thêm sản phẩm vào giỏ hàng hoặc tăng số lượng nếu sản phẩm đã tồn tại.
     */
    public function addProductToCart($productId)
    {
        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $cartProduct = $this->cartProducts()->where('product_id', $productId)->first();

        if ($cartProduct) {
            // Nếu có rồi, tăng số lượng sản phẩm
            $cartProduct->quantity += 1;
            $cartProduct->save();
        } else {
            // Nếu chưa có, tạo mới sản phẩm trong giỏ hàng
            $this->cartProducts()->create([
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
    }

    /**
     * Lấy giỏ hàng của người dùng hiện tại (nếu chưa có thì tạo mới)
     */
    public static function getOrCreateCart()
    {
        return self::firstOrCreate(['user_id' => Auth::id()]);
    }


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
            ->with(['cartProducts' => function ($query) {
                $query->whereHas('product');
            }])
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
