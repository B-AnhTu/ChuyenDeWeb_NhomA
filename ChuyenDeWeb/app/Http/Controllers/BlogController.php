<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Services\Blog\BlogSortAndSearch;
use App\Services\Blog\BlogService;
use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Khai báo thuộc tính blogService và blogSortAndSearch
    private $blogService, $blogSortAndSearch;

    // Constructor
    public function __construct(BlogService $blogService, BlogSortAndSearch $blogSortAndSearch)
    {
        $this->blogService = $blogService;
        $this->blogSortAndSearch = $blogSortAndSearch;
    }
    /**
     * Display a list of blogs with optional sorting and searching.
     */
    public function index(Request $request, $slug = null)
    {
        // Lấy 5 bài viết gần đây
        $recent_posts = Blog::getRecentPosts();

        // Nếu có slug, tìm bài viết theo slug
        if ($slug) {
            $blog = Blog::findBySlug($slug);

            if (!$blog) {
                return view('404');
            }

            return view('detail_blog', [
                'blog' => $blog,
                'recent_posts' => $recent_posts
            ]);
        }

        $searchTerm = $request->input('query');
        $perPage = 6;

        if ($searchTerm) {
            $data_blog = Blog::searchFullText($searchTerm)->paginate($perPage);
        } else {
            $data_blog = Blog::getAllBlogsQuery()->paginate($perPage); 
        }

        // Lấy tất cả danh mục (Có thể dùng trong phần lọc bài viết)
        $data_cate = Category::all();

        return view('blog', [
            'data_blog' => $data_blog,
            'data_cate' => $data_cate,
            'recent_posts' => $recent_posts,
            'currentPage' => $data_blog->currentPage(),
            'totalPages' => $data_blog->lastPage(),
            'searchTerm' => $searchTerm
        ]);
    }

    /**
     * Hiển thị blog kèm theo tìm kiếm và sắp xếp
     */
    public function list(Request $request)
    {
        // Lấy từ khóa tìm kiếm và lựa chọn sắp xếp từ request
        $searchTerm = $request->input('query');
        $sortBy = $request->input('sort_by');

        // Kết hợp tìm kiếm và sắp xếp với phân trang
        $data_blog = Blog::searchAndSort($searchTerm, $sortBy)->paginate(5); // 5 bài viết mỗi trang

        return view('blogAdmin', [
            'data_blog' => $data_blog,
            'filters' => [
                'searchTerm' => $searchTerm,
                'sort_by' => $sortBy,
            ],
        ]);
    }
    public function create(){
        return view('blogCreate');
    }

    /**
     * Lưu blog vào database
     */
    public function store(StoreBlogRequest $request)
    {
        $this->blogService->createBlog($request->validated());
        return redirect()->route('blogAdmin.index')->with('success', 'Blog created successfully.');
    }
    public function show($slug){
        $blog = Blog::where('slug', $slug)->first();
        if (!$blog){
            return redirect()->route('blogAdmin.index')->with('error', 'Blog not found. It may have been deleted or modified by another user.');  
        }
        return view('blogUpdate', compact('blog'));
    }
    /**
     * Hiển thị form chỉnh sửa blog
     */
    public function edit($slug){
        
        $blog = Blog::where('slug', $slug)->first();
        if (!$blog){
            return redirect()->route('blogAdmin.index')->with('error', 'Blog not found. It may have been deleted or modified by another user.');  
        }
        return view('blogUpdate', compact('blog'));
    }

    /**
     * Cập nhật blog
     */
    public function update(UpdateBlogRequest $request, $slug)
    {
        try {
            // Tìm blog theo slug
            $blog = Blog::where('slug', $slug)->first();
            // Kiểm tra nếu blog không tồn tại hoặc đã bị chỉnh sửa
            if (!$blog) {
                Session::flash('error', 'Blog not found. It may have been deleted or modified by another user.');
                return redirect()->route('blogAdmin.index')->withInput();
            }
            $this->blogService->updateBlog($blog, $request->validated());
            
            // Thông báo thành công
            Session::flash('success', 'Blog updated successfully.');
            return redirect()->route('blogAdmin.index');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('blogAdmin.index')->withInput();
        }
    }

    /**
     * Xóa 1 blog
     */
    public function destroy($slug)
    {
        try {
            $this->blogService->deleteBlog($slug);
            // Thông báo thành công
            return redirect()->route('blogAdmin.index')->with('success', 'Blog deleted successfully.');
        } 
        catch (\Exception $e){
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('blogAdmin.index')->withInput(); 
        }
    }
}
