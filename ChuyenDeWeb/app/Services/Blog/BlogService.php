<?php
namespace App\Services\Blog;

use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use App\Services\SlugService;
use Illuminate\Support\Facades\DB;


class BlogService
{
    protected $slugService; // Khai báo thuộc tính

    // Constructor để nhận SlugService thông qua dependency injection
    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService; // Khởi tạo thuộc tính slugService
    }
    public function getAllBlogs($perPage, $searchTerm = null)
    {
        if ($searchTerm) {
            return Blog::searchFullText($searchTerm)->paginate($perPage);
        }
        return Blog::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function createBlog($validatedData)
    {
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/blog'), $filename);
            $validatedData['image'] = $filename;
        }

        $validatedData['slug'] = $this->slugService->slugify($validatedData['title']);
        $validatedData['user_id'] = Auth::id();
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        return Blog::create($validatedData);
    }

    public function updateBlog($blog, $validatedData)
    {
        return DB::transaction(function () use ($blog, $validatedData) {
            // Lưu giá trị updated_at hiện tại trước khi cập nhật
            $currentUpdatedAt = $blog->updated_at;

            // Kiểm tra slug mới từ title
            $newSlug = $this->slugService->slugify($validatedData['title']);
            $slugChanged = $newSlug !== $blog->slug; // Kiểm tra slug đã thay đổi

            // 1. Kiểm tra xung đột trước khi thực hiện cập nhật
            if ($currentUpdatedAt != $blog->updated_at) {
                throw new \Exception('Conflict detected. The blog has been updated by another user.');
            }

            // 2. Nếu slug đã thay đổi, cập nhật slug
            if ($slugChanged) {
                $validatedData['slug'] = $newSlug; // Đặt slug mới vào validatedData
            }

            // 3. Cập nhật thông tin cho blog
            if (isset($validatedData['image'])) {
                $file = $validatedData['image'];
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/blog'), $filename);

                // Xóa ảnh cũ nếu có
                if ($blog->image && file_exists(public_path('img/blog/' . $blog->image))) {
                    unlink(public_path('img/blog/' . $blog->image));
                }

                $validatedData['image'] = $filename;
            }

            // 4. Cập nhật blog
            $blog->update($validatedData);

            // 5. Kiểm tra nếu slug đã thay đổi sau khi cập nhật
            if ($slugChanged) {
                // Kiểm tra xem bỏ sót slug đã thay đổi ngay sau khi cập nhật
                $freshBlog = Blog::where('slug', $blog->slug)->first(); // Lấy blog mới từ cơ sở dữ liệu
                if ($freshBlog->slug !== $newSlug) {
                    session()->flash('error', 'Conflict detected: The slug has been changed by another user.');
                    return redirect()->route('blogAdmin.index'); // Chuyển hướng về danh sách blogs
                }
            }

            return $blog; // Trả về blog đã cập nhật
        });
    }

    public function deleteBlog($slug)
    {
        // Tìm blog theo slug
        $blog = Blog::where('slug', $slug)->first();

        // Kiểm tra xem blog có tồn tại không
        if (!$blog) {
            throw new \Exception('Blog not found. It may have already been deleted.');
        }

        // Thực hiện xóa blog
        try {
            if (!$blog->delete()) {
                throw new \Exception('Failed to delete the blog. Please try again.');
            }
            // Kiểm tra và xóa hình ảnh nếu có
            if ($blog->image && file_exists(public_path('img/blog/' . $blog->image))) {
                unlink(public_path('img/blog/' . $blog->image));
            }
        } catch (\Exception $e) {
            // Xử lý lỗi trong trường hợp xóa không thành công
            throw new \Exception('An error occurred while trying to delete the blog: ' . $e->getMessage());
        }

        // Trả về true nếu xóa thành công
        return true;
    }
}
