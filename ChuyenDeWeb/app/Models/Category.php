<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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


    /**
     * Lấy category theo slug
     */
    public static function getCategoryBySlug($slug)
    {
        return self::where('slug', $slug)->first();
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
    public static function updateWithConflictCheck(array $data)
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
        $category = Category::getCategoryBySlug($slug);
        if ($category) {
            // Kiểm tra và xóa hình ảnh nếu có
            if ($category->image && file_exists(public_path('img/category/' . $category->image))) {
                unlink(public_path('img/category/' . $category->image));
            }
            $category->delete();
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

