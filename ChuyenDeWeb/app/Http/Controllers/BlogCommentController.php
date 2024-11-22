<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = BlogComment::getApprovedComments(); // Gọi phương thức trong model
        return view('blog', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Lưu bình luận mới cho bài viết.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'blog_id' => 'required|exists:blog,blog_id',
            'comment' => 'required|string'
        ]);

        // Gọi phương thức tạo bình luận trong model BlogComment
        BlogComment::createComment([
            'blog_id' => $request->input('blog_id'),
            'comment' => $request->input('comment')
        ]);

        return response()->json(['message' => 'Bình luận thành công! Vui lòng chờ duyệt!']);
    }
    /**
     * Hiển thị chi tiết bài viết.
     */
    public function show($slug)
    {
        // Gọi phương thức từ model Blog để lấy bài viết và bình luận
        $data = Blog::getBlogWithComments($slug);

        // Trả về view với dữ liệu bài viết và bình luận
        return view('detail_blog', $data);
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
     * Xóa bình luận.
     */
    public function destroy($id)
    {
        BlogComment::deleteComment($id);

        return redirect()->back()->with('message', 'Xóa bình luận thành công!');
    }

    /**
     * Duyệt bình luận.
     */
    public function approve($id)
    {
        BlogComment::approveComment($id);

        return redirect()->back()->with('message', 'Duyệt bình luận thành công!');
    }

    /**
     * Từ chối bình luận.
     */
    public function disapprove($id)
    {
        BlogComment::disapproveComment($id);

        return redirect()->back()->with('message', 'Bình luận đã bị từ chối!');
    }

    /**
     * Quản lý bình luận đã phê duyệt.
     */
    public function manageComments()
    {
        $comments = BlogComment::getApprovedComment();

        return view('comments.manageBlogComment', compact('comments'));
    }

    /**
     * Quản lý bình luận chưa phê duyệt.
     */
    public function unapprovedComments()
    {
        $comments = BlogComment::getUnapprovedComments();

        return view('comments.unapprovedBlogComment', compact('comments'));
    }
}
