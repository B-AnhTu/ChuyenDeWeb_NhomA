<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use App\Services\SlugService;


class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    protected $primaryKey = 'blog_id';

    protected $fillable = ['title', 'slug', 'short_description', 'image', 'content', 'user_id', 'created_at', 'updated_at'];

    public static function getAllBlogsQuery()
    {
        return self::query()->orderBy('created_at', 'desc');
    }

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

    // Phương thức lấy bài viết theo slug
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    // Phương thức lấy các bài viết gần đây
    public static function getRecentPosts($limit = 5)
    {
        return self::orderBy('created_at', 'desc')->take($limit)->get();
    }

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
    // Lấy blog bằng id
    public static function getBlogById($id)
    {
        return self::find($id);
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
        $blog_id = self::decodeSlug($slug);
        $blog = self::find($blog_id);
        if ($blog) {
            // Kiểm tra và xóa hình ảnh nếu có
            if ($blog->image && file_exists(public_path('img/blog/' . $blog->image))) {
                unlink(public_path('img/blog/' . $blog->image));
            }
            $blog->delete();
            return true;
        }
    }
    /**
     * Sắp xếp
     */
    public static function sortBlogs($query, $sortBy)
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
            case 'created_at_asc':
                return $query->orderBy('created_at', 'asc');
            case 'created_at_desc':
                return $query->orderBy('created_at', 'desc');
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }

    /**
     * Tìm kiếm full text search (trên admin)
     */
    public static function search($query, $searchTerm)
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
    public static function scopeSearchAndSort($query, $searchTerm = null, $sortBy = null)
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
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc'); // Sắp xếp mặc định
            }
        }

        return $query;
    }

    // Hàm kiểm tra khởi tạo và cập nhật slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = static::generateUniqueSlug($blog->title, $blog->blog_id);
        });

        static::updating(function ($blog) {
            $blog->slug = static::generateUniqueSlug($blog->title, $blog->blog_id);
        });
    }

    // Tạo slug không trùng lặp
    protected static function generateUniqueSlug($title, $blogId = null)
    {
        // Tạo slug từ title
        $slug = SlugService::slugify($title);

        // Mã hóa ID người dùng
        $encodedId = base64_encode($blogId); // Mã hóa ID người dùng

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

        return $decodedId; // Trả về ID người dùng
    }

    public static function getBlogWithComments($slug)
    {
        // Lấy bài viết theo slug
        $blog = self::where('slug', $slug)->firstOrFail();

        // Lấy bình luận đã duyệt cho bài viết
        $comments = BlogComment::where('blog_id', $blog->blog_id)
            ->where('status', 1) // Bình luận đã duyệt
            ->orderBy('created_at', 'desc')
            ->get();

        // Trả về bài viết cùng với bình luận
        return compact('blog', 'comments');
    }   
}
