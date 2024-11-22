<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = ['blog_id', 'user_id', 'content', 'status', 'name', 'email'];

    protected $table = 'blog_comment';

    protected $primaryKey = 'comment_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    public static function getApprovedComments()
    {
        return self::with('user') // Lấy thông tin người dùng
            ->where('status', 1) // Chỉ lấy bình luận đã duyệt
            ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo giảm dần
            ->get(); // Lấy tất cả bình luận
    }

    public static function createComment($data)
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        // Tạo bình luận mới
        return self::create([
            'blog_id' => $data['blog_id'],
            'user_id' => $user->user_id, // Lưu id của người dùng
            'content' => $data['comment'],
            'name' => $user->fullname, // Lưu tên của người dùng
            'email' => $user->email, // Lưu email của người dùng
            'status' => 0 // Trạng thái mặc định là chưa phê duyệt
        ]);
    }

    /**
     * Xóa bình luận.
     *
     * @param  int  $id
     * @return void
     */
    public static function deleteComment($id)
    {
        $comment = self::findOrFail($id);
        $comment->delete();
    }

    /**
     * Duyệt bình luận.
     *
     * @param  int  $id
     * @return void
     */
    public static function approveComment($id)
    {
        $comment = self::findOrFail($id);
        $comment->status = 1; // Đánh dấu là đã phê duyệt
        $comment->save();
    }

    /**
     * Từ chối bình luận.
     *
     * @param  int  $id
     * @return void
     */
    public static function disapproveComment($id)
    {
        $comment = self::findOrFail($id);
        $comment->status = 0; // Đánh dấu là chưa phê duyệt
        $comment->save();
    }

    /**
     * Lấy các bình luận đã phê duyệt.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getApprovedComment()
    {
        return self::with('user')->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Lấy các bình luận chưa phê duyệt.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUnapprovedComments()
    {
        return self::with('user')->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}
