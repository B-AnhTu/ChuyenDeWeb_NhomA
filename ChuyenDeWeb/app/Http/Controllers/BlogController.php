<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function list()
    {
        $data_blog = Blog::with('user')->paginate(5);
        return view('blogAdmin', ['data_blog' => $data_blog]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blogCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'short_description' => 'required|max:255',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ],[
            'image.required' => 'Vui lòng chọn hình ảnh để tải lên',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max' => 'Tiêu đề không được quá 100 ký tự',
            'short_description.required' => 'Vui lòng nhập mô tả ngắn',
            'short_description.max' => 'Mô tả ngắn không được quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/blog'), $filename);

            // Cập nhật ảnh mới trong database
            $data['image'] = $filename;
        }

        $blog = Blog::create([
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'content' => $data['content'],
            'image' => $data['image'],
            'user_id' => Auth::user()->user_id,
        ]);
        $blog->save();

        return redirect()->route('blogAdmin.index')->with('success', 'Blog created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('blogShow', ['blog' => $blog]);  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        return view('blogUpdate', ['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|max:100',
            'short_description' => 'required|max:255',
            'content' => 'required',
        ],[
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max' => 'Tiêu đề không được quá 100 ký tự',
            'short_description.required' => 'Vui lòng nhập mô tả ngắn',
            'short_description.max' => 'Mô tả ngắn không được quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung',
        ]);

        $blog = Blog::findOrFail($id);

        if(!$blog){
            return redirect()->route('blogAdmin.index')->with('error', 'Blog not found');
        }

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/blog'), $filename);

            // Delete old image if exists
            if ($blog->image && file_exists(public_path('img/blog/' . $blog->image))) {
                unlink(public_path('img/blog/' . $blog->image));
            }

            // Update with new image
            $blog->image = $filename;
        }

        $blog->title = $request->input('title');
        $blog->short_description = $request->input('short_description');
        $blog->content = $request->input('content');
        $blog->updated_at = now();
        $blog->save();

        return redirect()->route('blogAdmin.index')->with('success', 'Blog updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Kiểm tra xem blog có tồn tại không
        $blog = Blog::findOrFail($id);
        if (!$blog) {
            return redirect()->route('blogAdmin.index')->with('error', 'Blog không tồn tại.');
        }
        // Delete image if exists
        if ($blog->image && file_exists(public_path('img/blog/' . $blog->image))) {
            unlink(public_path('img/blog/' . $blog->image));
        }
        // Thực hiện xóa blog
        try {
            $blog->delete();
            return redirect()->route('blogAdmin.index')->with('success', 'Blog đã được xóa thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi khi xóa không thành công
            return redirect()->route('blogAdmin.index')->with('error', 'Xóa blog không thành công.');
        }
    }
}
