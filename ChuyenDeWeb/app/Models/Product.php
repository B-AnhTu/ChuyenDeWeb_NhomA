<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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

    // lấy tất cả sản phẩm trong trang index
    public static function getAllProducts($perPage = 8)
    {
        return self::orderBy('created_at', 'desc')->paginate($perPage);
    }

    // lấy tất cả sản phẩm trong trang index
    public static function getAllProductsViewProduct($perPage = 6)
    {
        return self::orderBy('created_at', 'desc')->paginate($perPage);
    }

    // lọc sản phẩm theo danh mục trang index
    public static function filterByManufacturer($manufacturerId, $perPage = 8)
    {
        return self::where('manufacturer_id', $manufacturerId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    // lọc sản phẩm theo danh mục trang product
    public static function filterByManufacturerViewProduct($manufacturerId, $perPage = 6)
    {
        return self::where('manufacturer_id', $manufacturerId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    // lọc sản phẩm theo loại sản phẩm trang index
    public static function filterByCategory($categoryId, $perPage = 8)
    {
        return self::where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    // lọc sản phẩm theo loại sản phẩm trang product
    public static function filterByCategoryViewProduct($categoryId, $perPage = 6)
    {
        return self::where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    //Tìm kiếm sản phẩm trang index
    public static function searchProducts($keyword = null, $manufacturerId = null, $perPage = 8)
    {
        $query = self::query();

        if ($keyword) {
            $searchWords = explode(' ', $keyword);
            $searchWords = array_filter($searchWords, fn($word) => strlen($word) >= 2);

            if (!empty($searchWords)) {
                $searchQuery = '+' . implode('* +', $searchWords) . '*';
                $query->whereRaw("MATCH(product_name, description) AGAINST(? IN BOOLEAN MODE)", [$searchQuery]);
            }
        }

        if ($manufacturerId) {
            $query->where('manufacturer_id', $manufacturerId);
        }

        return $query->orderBy('product_view', 'desc')
            ->orderBy('sold_quantity', 'desc')
            ->paginate($perPage);
    }

    // tìm kiếm sản phẩm trang product
    public static function searchProductsViewProduct($keyword = null, $manufacturerId = null, $perPage = 6)
    {
        $query = self::query();

        if ($keyword) {
            $searchWords = explode(' ', $keyword);
            $searchWords = array_filter($searchWords, fn($word) => strlen($word) >= 2);

            if (!empty($searchWords)) {
                $searchQuery = '+' . implode('* +', $searchWords) . '*';
                $query->whereRaw("MATCH(product_name, description) AGAINST(? IN BOOLEAN MODE)", [$searchQuery]);
            }
        }

        if ($manufacturerId) {
            $query->where('manufacturer_id', $manufacturerId);
        }

        return $query->orderBy('product_view', 'desc')
            ->orderBy('sold_quantity', 'desc')
            ->paginate($perPage);
    }

    // sắp xếp sản phẩm trang index
    public static function sortProducts($sortBy = null, $manufacturerId = null, $keyword = null, $perPage = 8)
    {
        $query = self::query();

        if ($manufacturerId) {
            $query->where('manufacturer_id', $manufacturerId);
        }

        if ($keyword) {
            $query->where('product_name', 'like', '%' . $keyword . '%');
        }

        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('product_name', 'desc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($perPage);
    }

    // sắp xếp sản phẩm trang product
    public static function sortProductsViewProduct($sortBy = null, $manufacturerId = null, $keyword = null, $perPage = 6)
    {
        $query = self::query();

        if ($manufacturerId) {
            $query->where('manufacturer_id', $manufacturerId);
        }

        if ($keyword) {
            $query->where('product_name', 'like', '%' . $keyword . '%');
        }

        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('product_name', 'desc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($perPage);
    }


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
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
}
