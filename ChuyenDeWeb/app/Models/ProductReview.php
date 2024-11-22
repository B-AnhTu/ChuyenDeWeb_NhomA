<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductReview extends Model
{
    use HasFactory;

    protected $table = 'product_review';
    protected $primaryKey = 'review_id';
    protected $fillable = ['user_id', 'product_id', 'comment', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Tạo một bình luận mới cho sản phẩm.
     *
     * @param array $data
     * @return ProductReview
     */
    public static function createReview($data)
    {
        if (!Auth::check()) {
            return [
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để bình luận'
            ];
        }

        $review = self::create([
            'user_id' => Auth::id(),
            'product_id' => $data['product_id'],
            'comment' => $data['comment'],
            'status' => 0 // Mặc định là chờ duyệt
        ]);

        return [
            'status' => 'success',
            'message' => 'Bình luận của bạn đã được gửi và đang chờ duyệt'
        ];
    }

    /**
     * Xóa bình luận.
     *
     * @param int $id
     * @return bool|null
     */
    public static function deleteReview($id)
    {
        $review = self::findOrFail($id);
        return $review->delete();
    }

    /**
     * Lấy bình luận đã duyệt của một sản phẩm.
     *
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getApprovedReviews($productId)
    {
        return self::with('user')
            ->where('product_id', $productId)
            ->where('status', 1) // Chỉ lấy các bình luận đã được duyệt
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($review) {
                return [
                    'user_name' => $review->user->fullname,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->format('d/m/Y H:i')
                ];
            });
    }

    /**
     * Lấy các bình luận chờ duyệt.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPendingReviews()
    {
        return self::with(['user', 'product'])
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Lấy các bình luận đã duyệt.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getApprovedReview()
    {
        return self::with(['user', 'product'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Duyệt bình luận.
     *
     * @param int $id
     * @return bool
     */
    public static function approveReview($id)
    {
        $review = self::findOrFail($id);
        $review->status = 1;
        return $review->save();
    }

    /**
     * Từ chối bình luận.
     *
     * @param int $id
     * @return bool
     */
    public static function rejectReview($id)
    {
        $review = self::findOrFail($id);
        $review->status = 2;
        return $review->save();
    }
}
