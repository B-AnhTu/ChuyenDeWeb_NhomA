<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewProductNotification;
use App\Models\NewsletterSubscriber;
use App\Services\SlugService;


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

    // Hàm khởi tạo và cập nhật slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = static::generateUniqueSlug($product->product_name, $product->product_id);
        });

        static::updating(function ($product) {
            $product->slug = static::generateUniqueSlug($product->product_name, $product->product_id);
        });
    }

    // Tạo slug không trùng lặp
    protected static function generateUniqueSlug($productName, $productId = null)
    {
        // Tạo slug từ fullname
        $slug = SlugService::slugify($productName);

        // Mã hóa ID người dùng
        $encodedId = base64_encode($productId); // Mã hóa ID người dùng

        // Tạo slug duy nhất bằng cách thêm ID đã mã hóa vào cuối slug
        $uniqueSlug = $slug . '_' . $encodedId;

        return $uniqueSlug; // Trả về slug duy nhất
    }

    // Phương thức giải mã slug để lấy ID sản phẩm
    public static function decodeSlug($slug)
    {
        // Tách slug thành phần
        $parts = explode('_', $slug);
        if (count($parts) < 2) {
            return null; // Nếu không có ID, trả về null
        }

        // Lấy phần cuối cùng (ID đã mã hóa)
        $encodedId = end($parts); // Lấy phần cuối cùng
        $decodedId = base64_decode($encodedId); // Giải mã base64

        return $decodedId; // Trả về ID sản phẩm
    }

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

    // Lấy sản phẩm theo id 
    public static function getProductById($productId){
        return self::find($productId);
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
    /**
     * Gửi thông báo đến các subscribers khi sản phẩm mới được tạo
     */
    public function notifySubscribers()
    {
        // Lấy danh sách subscribers đang hoạt động
        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        // Gửi email thông báo đến từng subscriber
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->send(new NewProductNotification($this));
        }
    }
    /**
     * Lấy sản phẩm dựa trên slug
     */
    public static function getProductBySlug($slug){
        $product = self::where('slug', $slug)->first();
        if ($product) {
            return $product;
        }

        return null; // Nếu không tìm thấy
    }
    /**
     * Thêm sản phẩm
     */
    public static function createProduct(array $data){
        // Tạo sản phẩm mới
        $product = self::create($data);

        // Gọi phương thức để gửi thông báo đến subscribers
        $product->notifySubscribers();

        return $product; // Trả về sản phẩm đã tạo
    }
    /**
     * Cập nhật sản phẩm với kiểm tra xung đột
     */
    public function updateWithConflictCheck(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Lưu giá trị updated_at hiện tại từ cơ sở dữ liệu
            $currentUpdatedAt = $this->updated_at;

            // Làm mới thông tin của sản phẩm từ cơ sở dữ liệu
            $this->refresh();

            // Kiểm tra xung đột bằng cách so sánh updated_at
            if ($currentUpdatedAt != $this->updated_at) {
                throw new \Exception('Conflict detected. The product has been updated by another user.');
            }

            // Xử lý cập nhật ảnh nếu có
            if (isset($data['image'])) {
                $file = $data['image'];
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/products'), $filename);

                // Xóa ảnh cũ nếu có
                if ($this->image && file_exists(public_path('img/products/' . $this->image))) {
                    unlink(public_path('img/products/' . $this->image));
                }

                $data['image'] = $filename;
            }

            // Cập nhật timestamp updated_at
            $data['updated_at'] = now();

            // Cập nhật dữ liệu
            $this->update($data);

            return $this; // Trả về sản phẩm đã cập nhật
        });
    }
    public static function getDeletedProducts(){
        return self::onlyTrashed()->paginate(5);
    }
    /**
     * Xóa sản phẩm (tạm thời)
     */
    public static function deleteProductBySlug($slug){
        $product_id = self::decodeSlug($slug);
        $product = self::getProductById($product_id);
        if ($product) {
            $product->delete();
            return true;
        }
    }
    /**
     * Khôi phục sản phẩm
     */
    public static function restoreProduct($slug){
        // Giải mã slug để lấy sản phẩm ID
        $productId = self::decodeSlug($slug);
        // Tìm sản phẩm đã xóa theo ID
        $product = self::onlyTrashed()->find($productId);
        if ($product) {
            $product->restore(); // Khôi phục sản phẩm
            return true; 
        }
        return false; 
    }
    /**
     * Xóa vĩnh viễn sản phẩm
     */
    public static function forceDeleteProduct($slug){
        // Giải mã slug để lấy sản phẩm ID
        $productId = self::decodeSlug($slug);
        // Tìm sản phẩm đã xóa theo ID
        $product = self::onlyTrashed()->find($productId);
        if ($product) {
            // Xóa ảnh nếu có
            if ($product->image && file_exists(public_path('img/products/' . $product->image))) {
                unlink(public_path('img/products/' . $product->image));
            }
            // Xóa sản phẩm
            $product->forceDelete();
            return true;
        }
        return false;
    }

    /**
     * Tìm kiếm sản phẩm theo từ khóa sử dụng full-text search
     */
    public static function search($searchTerm)
    {
        if ($searchTerm) {
            // Tách từ khóa và lọc từ ngắn
            $searchWords = explode(' ', $searchTerm);
            $searchWords = array_filter($searchWords, function ($word) {
                return strlen($word) >= 2;
            });

            if (!empty($searchWords)) {
                // Tạo truy vấn fulltext
                $searchQuery = '+' . implode('* +', $searchWords) . '*';
                return self::whereRaw("MATCH(product_name, description) AGAINST(? IN BOOLEAN MODE)", [$searchQuery])
                    ->orderByRaw("CASE WHEN product_name LIKE ? THEN 1 ELSE 2 END", ["%$searchTerm%"]);
            }
        }
        return self::getAllProducts(); // Nếu không có từ khóa, trả về tất cả sản phẩm
    }
    /**
     * Sắp xếp 
     */
    public static function sort($query, $sortBy){
        // Sắp xếp theo yêu cầu
            switch ($sortBy) {
                case 'name_asc':
                    $query->orderBy('product_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('product_name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'views_asc':
                    $query->orderBy('product_view', 'asc');
                    break;
                case 'views_desc':
                    $query->orderBy('product_view', 'desc');
                    break;
                case 'purchases_asc':
                    $query->orderBy('sold_quantity', 'asc');
                    break;
                case 'purchases_desc':
                    $query->orderBy('sold_quantity', 'desc');
                    break;
                case 'stock_asc':
                    $query->orderBy('stock_quantity', 'asc');
                    break;
                case 'stock_desc':
                    $query->orderBy('stock_quantity', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc'); // Sắp xếp mặc định
                    break;
            }
        return $query;
    }

}
