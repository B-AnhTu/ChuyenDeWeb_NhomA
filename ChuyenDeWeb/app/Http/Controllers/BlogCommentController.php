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
        $comments = BlogComment::with('user')->where('status', 1)->orderBy('created_at', 'desc')->get();
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

        return response()->json(['message' => 'Bình luận thành công! Vui lòng chờ duyệt!']);
    }
    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $comments = BlogComment::where('blog_id', $blog->blog_id)
            ->where('status', 1)
            ->orderBy('created_at', 'desc') // Thêm dòng này
            ->get();
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

        return redirect()->back()->with('message', 'Xóa bình luận thành công!');
    }

    public function approve($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->status = 1; // Cập nhật trạng thái bình luận thành đã phê duyệt
        $comment->save();

        return redirect()->back()->with('message', 'Duyệt bình luận thành công!');
    }

    public function disapprove($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->status = 0;
        $comment->save();

        return redirect()->back()->with('message', 'Bình luận đã bị từ chối!');
    }

    public function manageComments()
    {
        $comments = BlogComment::with('user')->where('status', 1)
            ->orderBy('created_at', 'desc') // Thêm dòng này
            ->paginate(10);
        return view('comments.manageBlogComment', compact('comments'));
    }

    public function unapprovedComments()
    {
        $comments = BlogComment::with('user')->where('status', 0)
            ->orderBy('created_at', 'desc') // Thêm dòng này
            ->paginate(10);
        return view('comments.unapprovedBlogComment', compact('comments'));
    }
}    
