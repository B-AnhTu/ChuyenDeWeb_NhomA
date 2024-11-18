<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'short_description', 'image', 'content', 'user_id', 'created_at', 'updated_at'];

    protected $table = 'blog';

    protected $primaryKey = 'blog_id';

    /**
     * Định nghĩa dữ liệu sẽ được lập chỉ mục.
     *
     * @return array
     */
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }

    //Hàm func lấy tất cả blog
    public static function getAllBlog()
    {
        return self::all();
    }
    //Hàm tìm kiếm full text
    public static function searchFullText($searchTerm)
    {
        $keywords = explode(' ', $searchTerm);
        return self::where(function ($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->orWhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('content', 'LIKE', "%{$keyword}%");
            }
        });
    }
    /**
     * Lấy tất cả blog với phân trang
     */
    public static function getPaginatedBlogs($perPage = 5, $searchTerm = null)
    {
        $query = self::query();

        if ($searchTerm) {
            $query->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content', 'LIKE', "%{$searchTerm}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    /**
     * Lấy blog theo slug
     */
    public static function getBlogBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * Tạo blog mới
     */
    public static function createBlog(array $data)
    {
        return self::create($data);
    }

    /**
     * Cập nhật blog
     */
    public function updateBlog(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Lưu giá trị updated_at hiện tại trước khi cập nhật
            $currentUpdatedAt = $this->updated_at;

            // Kiểm tra slug mới từ category_name
            $newSlug = $data['slug'] ?? $this->slug; // Lấy slug mới từ dữ liệu
            $slugChanged = $newSlug !== $this->slug; // Kiểm tra slug đã thay đổi

            // 1. Kiểm tra xung đột trước khi thực hiện cập nhật
            if ($currentUpdatedAt != $this->updated_at) {
                throw new \Exception('Conflict detected. The category has been updated by another user.');
            }

            // 2. Cập nhật thông tin cho category
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
     * Xóa blog
     */
    public static function deleteBlogBySlug($slug)
    {
        $blog = self::where('slug', $slug)->first();
        if ($blog) {
            $blog->delete();
        }
    }
    /**
     * Sắp xếp
     */
    public function sortBlogs($query, $sortBy)
    {
        switch ($sortBy) {
            case 'name_asc':
                return $query->orderBy('title', 'asc');
            case 'name_desc':
                return $query->orderBy('title', 'desc');
            case 'description_asc':
                return $query->orderBy('short_description', 'asc');
            case 'description_desc':
                return $query->orderBy('short_description', 'desc');
            case 'updated_at_asc':
                return $query->orderBy('updated_at', 'asc');
            case 'updated_at_desc':
                return $query->orderBy('updated_at', 'desc');
            default:
                return $query;
        }
    }

    /**
     * Tìm kiếm full text search (trên admin)
     */
    public function search($query, $searchTerm)
    {
        if ($searchTerm) {
            $searchWords = explode(' ', $searchTerm);
            $searchWords = array_filter($searchWords, fn($word) => strlen($word) >= 2);

            if (!empty($searchWords)) {
                $searchQuery = '+' . implode('* +', $searchWords) . '*';
                return $query->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$searchQuery]);
            }
        }

        return $query;
    }
    // Áp dụng vừa tìm kiếm vừa sắp xếp blog
    public function scopeSearchAndSort($query, $searchTerm = null, $sortBy = null)
    {
        // Tìm kiếm nếu có từ khóa
        if ($searchTerm) {
            $searchWords = explode(' ', $searchTerm);
            $searchWords = array_filter($searchWords, fn($word) => strlen($word) >= 2);

            if (!empty($searchWords)) {
                $searchQuery = '+' . implode('* +', $searchWords) . '*';
                $query->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$searchQuery]);
            }
        }

        // Sắp xếp nếu có lựa chọn
        if ($sortBy) {
            switch ($sortBy) {
                case 'name_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'description_asc':
                    $query->orderBy('short_description', 'asc');
                    break;
                case 'description_desc':
                    $query->orderBy('short_description', 'desc');
                    break;
                case 'updated_at_asc':
                    $query->orderBy('updated_at', 'asc');
                    break;
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc'); // Sắp xếp mặc định
            }
        }

        return $query;
    }
}
