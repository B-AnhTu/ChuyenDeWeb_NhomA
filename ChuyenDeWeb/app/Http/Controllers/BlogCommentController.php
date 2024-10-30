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
        $comments = BlogComment::with('user')->where('status', 1)->get(); // load approved comments
        return view('comments.index', compact('comments'));
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
        $request->validate([
            'blog_id' => 'required|exists:blog,blog_id',
            'comment' => 'required|string'
        ]);

        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        BlogComment::create([
            'blog_id' => $request->input('blog_id'),
            'user_id' => $user->user_id, // Lưu id của người dùng
            'content' => $request->input('comment'),
            'name' => $user->fullname, // Lưu tên của người dùng
            'email' => $user->email, // Lưu email của người dùng
            'status' => 0 // Trạng thái mặc định là chưa phê duyệt
        ]);

        return redirect()->route('comments.unapproved')->with('message', 'Comment submitted successfully! Waiting for approval.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        $comments = BlogComment::with('user')->where('blog_id', $id)->where('status', 1)->get();

        return view('detail_blog', compact('blog', 'comments'));
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
    public function destroy($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('message', 'Comment deleted successfully!');
    }

    public function approve($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->status = 1; // Cập nhật trạng thái bình luận thành đã phê duyệt
        $comment->save();

        return redirect()->back()->with('message', 'Comment approved successfully!');
    }

    public function disapprove($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->status = 0;
        $comment->save();

        return redirect()->back()->with('message', 'Comment disapproved successfully!');
    }

    public function manageComments()
    {
        // Lấy bình luận đã được phê duyệt cùng với thông tin người dùng
        $comments = BlogComment::with('user')->where('status', 1)->paginate(10);
        return view('comments.manageBlogComment', compact('comments'));
    }

    public function unapprovedComments()
    {
        $comments = BlogComment::with('user')->where('status', 0)->paginate(10); // load unapproved comments
        return view('comments.unapprovedBlogComment', compact('comments'));
    }
}
