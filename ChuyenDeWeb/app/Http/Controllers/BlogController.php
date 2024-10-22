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
    public function index($id = null)
    {
        // Trường hợp có ID (chi tiết blog)
        if ($id) {
            $blog = Blog::where('blog_id', $id)->first();


            // Nếu không tìm thấy blog với ID đã cho, trả về lỗi 404
            if (!$blog) {
                return view('404');
            }

            return view('detail_blog', ['blog' => $blog]);
        }

        // Trường hợp không có ID (danh sách blog)
        $data_blog = Blog::getAllBlog();
        $data_cate = Category::getAllCate();

        return view('blog', ['data_blog' => $data_blog, 'data_cate' => $data_cate]);
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
