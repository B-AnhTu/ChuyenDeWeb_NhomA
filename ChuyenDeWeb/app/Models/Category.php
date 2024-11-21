<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\SlugService;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'category_id';

    // Các cột được phép gán dữ liệu hàng loạt
    protected $fillable = [
        'category_name',
        'image',
        'slug',
    ];

    // Hàm khởi tạo và cập nhật slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = static::generateUniqueSlug($category->category_name, $category->category_id);
        });

        static::updating(function ($category) {
            $category->slug = static::generateUniqueSlug($category->category_name, $category->category_id);
        });
    }

    // Tạo slug không trùng lặp
    protected static function generateUniqueSlug($categoryName, $categoryId = null)
    {
        // Tạo slug từ product name
        $slug = SlugService::slugify($categoryName);

        // Mã hóa ID danh mục
        $encodedId = base64_encode($categoryId); // Mã hóa ID danh mục

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

        return $decodedId; // Trả về ID 
    }

    // Phương thức lấy tất cả category
    public static function getAllCate()
    {
        return self::all();
    }
    /** 
     * Phương thức lấy tất cả category (có phân trang) 
     * */
    public static function getAllCategory()
    {
        return self::paginate(5);
    }

    public static function getCategoryById($id){
        return self::find($id);
    }

    /**
     * Tạo blog mới
     */
    public static function createCategory(array $data)
    {
        return self::create($data);
    }

    /**
     * Cập nhật Category
     */
    public function updateWithConflictCheck(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Lưu giá trị updated_at hiện tại trước khi cập nhật
            $currentUpdatedAt = $this->updated_at;

            // Kiểm tra xung đột trước khi thực hiện cập nhật
            if ($currentUpdatedAt != $this->updated_at) {
                throw new \Exception('Conflict detected. The category has been updated by another user.');
            }

            // Kiểm tra slug mới từ category_name
            $newSlug = $data['slug'] ?? $this->slug; // Lấy slug mới từ dữ liệu
            $slugChanged = $newSlug !== $this->slug; // Kiểm tra slug đã thay đổi

            // Cập nhật thông tin cho category
            if (isset($data['image'])) {
                $file = $data['image'];
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/category'), $filename);

                // Xóa ảnh cũ nếu có
                if ($this->image && file_exists(public_path('img/category/' . $this->image))) {
                    unlink(public_path('img/category/' . $this->image));
                }

                $data['image'] = $filename;
            }

            $data['updated_at'] = now();

            // 3. Cập nhật category
            $this->update($data);

            return $this; // Trả về category đã cập nhật
        });
    }

    /**
     * Xóa Category
     */
    public static function deleteCategoryBySlug($slug)
    {
        $category_id = self::decodeSlug($slug);
        $category = self::getCategoryById($category_id);
        if ($category) {
            // Kiểm tra và xóa hình ảnh nếu có
            if ($category->image && file_exists(public_path('img/category/' . $category->image))) {
                unlink(public_path('img/category/' . $category->image));
            }
            $category->delete();
            return true;
        }
    }
    /**
     * Tìm kiếm
     */
    public static function search($searchTerm)
    {
        if ($searchTerm) {
            return self::where('category_name', 'like', '%' . $searchTerm . '%');
        }
        return self::all();
    }
    /**
     * Sắp xếp
     */
    public static function sortCategories($query, $sortBy)
    {
        // Sắp xếp theo yêu cầu
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('category_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('category_name', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                // Mặc định 
                $query->orderBy('created_at', 'desc');
                break;
        }
        return $query;
    }
}

