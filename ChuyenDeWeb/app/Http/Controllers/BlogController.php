<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $slug = null)
    {
        // Lấy bài viết mới nhất cho sidebar
        $recent_posts = Blog::orderBy('created_at', 'desc')->take(5)->get();

        // Trường hợp có slug (chi tiết blog)
        if ($slug) {
            try {
                // Tìm bài viết theo slug
                $blog = Blog::where('slug', $slug)->first();

                if (!$blog) {
                    return view('404');
                }

                return view('detail_blog', [
                    'blog' => $blog,
                    'recent_posts' => $recent_posts
                ]);
            } catch (\Exception $e) {
                // Nếu có lỗi, chuyển đến trang 404
                return view('404');
            }
        }

        // Số bài viết trên mỗi trang
        $perPage = 6;
        // Lấy số trang hiện tại từ query parameter, mặc định là 1
        $currentPage = $request->query('page', 1);

        // Lấy tất cả bài viết và phân trang
        $data_blog = Blog::orderBy('created_at', 'desc')
            ->skip(($currentPage - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Lấy tổng số bài viết để tính số trang
        $totalPosts = Blog::count();
        $totalPages = ceil($totalPosts / $perPage);

        // Kiểm tra nếu trang hiện tại vượt quá tổng số trang
        if ($currentPage > $totalPages && $totalPages > 0) {
            return redirect()->route('blog.index', ['page' => 1]);
        }

        $data_cate = Category::getAllCate();

        return view('blog', [
            'data_blog' => $data_blog,
            'data_cate' => $data_cate,
            'recent_posts' => $recent_posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
