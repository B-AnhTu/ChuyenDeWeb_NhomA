<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    use HasFactory;

    protected $table = 'cart_product';
    protected $primaryKey = 'cart_product_id';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity'
    ];

    // Quan hệ với bảng Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    // Quan hệ với bảng Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

