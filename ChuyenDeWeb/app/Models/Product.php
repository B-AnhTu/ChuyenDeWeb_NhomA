<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'manufacturer_id',
        'product_view',
        'image',
        'sold_quantity',
        'slug',
    ];


    public function setProductNameAttribute($value)
    {
        $this->attributes['product_name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }


    // Quan hệ với bảng Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ với bảng Manufacturer
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    // Quan hệ với bảng CartProduct
    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'product_id');
    }

    // Quan hệ với bảng ProductLike
    public function productLikes()
    {
        return $this->hasMany(ProductLike::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }
}
