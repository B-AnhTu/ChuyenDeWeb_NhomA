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
    public function getBlogBySlug($slug){
        $blogId = Blog::decodeSlug($slug); // Giải mã slug để lấy ID sản phẩm
        return Blog::find($blogId);
    }

    public function createBlog($validatedData)
    {
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/blog'), $filename);
            $validatedData['image'] = $filename;
        }

        $validatedData['user_id'] = Auth::id();
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        // Tạo người dùng mới
        $blog = Blog::createBlog($validatedData);

        // Tạo slug cho người dùng sau khi đã tạo
        $blog->slug = Blog::generateUniqueSlug($blog->title, $blog->blog_id);
        $blog->save();

        return $blog; // Trả về người dùng đã tạo
    }

    public function updateBlog($blog, $validatedData)
    {
        // Kiểm tra xem image có tồn tại trong validated data
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/blog'), $filename);
            $validatedData['image'] = $filename;
        }

        $validatedData['updated_at'] = now();

        //Cập nhật thông tin
        $blog->updateBlog($validatedData);

        // Tạo lại slug cho người dùng
        if ($blog->title !== $validatedData['title']) {
            $blog->slug = Blog::generateUniqueSlug($blog->title, $blog->blog_id);
        }
        $blog->save();

        return $blog; // Trả về người dùng đã cập nhật
    }

    public function deleteBlog($slug)
    {
        return Blog::deleteBlogBySlug($slug);
    }
}
