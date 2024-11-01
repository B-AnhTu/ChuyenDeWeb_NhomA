<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để bình luận'
            ], 401);
        }

        $review = ProductReview::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'comment' => $request->comment,
            'status' => 0 // Mặc định là chờ duyệt
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bình luận của bạn đã được gửi và đang chờ duyệt'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['reviews' => function ($query) {
                $query->where('status', 1) // Chỉ lấy bình luận đã duyệt
                    ->orderBy('created_at', 'desc'); // Sắp xếp bình luận mới nhất ở đầu
            }, 'reviews.user'])
            ->firstOrFail();

        return view('productDetail', compact('product'));
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
        ProductReview::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Bình luận đã được xóa');
    }

    public function getReviews($productId)
    {
        $reviews = ProductReview::with('user')
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

        return response()->json([
            'status' => 'success',
            'data' => $reviews
        ]);
    }


    public function pendingReviews()
    {
        $reviews = ProductReview::with(['user', 'product'])
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reviews.pending', compact('reviews'));
    }

    public function approvedReviews()
    {
        $reviews = ProductReview::with(['user', 'product'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reviews.approved', compact('reviews'));
    }

    public function approve($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->status = 1;
        $review->save();

        return redirect()->back()->with('success', 'Bình luận đã được duyệt');
    }

    public function reject($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->status = 2;
        $review->save();

        return redirect()->back()->with('success', 'Bình luận đã bị từ chối');
    }
}
