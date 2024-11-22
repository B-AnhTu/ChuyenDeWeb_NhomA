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
     * Lưu bình luận của người dùng.
     */
    public function store(Request $request)
    {
        // Gọi phương thức createReview từ model ProductReview
        $response = ProductReview::createReview($request->all());

        return response()->json($response);
    }

    /**
     * Hiển thị thông tin sản phẩm và các bình luận đã duyệt.
     */
    public function show($slug)
    {
        // Gọi phương thức trong model để lấy sản phẩm và bình luận
        $product = Product::getProductWithReviews($slug);

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
     * Xóa bình luận.
     */
    public function destroy($id)
    {
        ProductReview::deleteReview($id);

        return redirect()->back()->with('success', 'Bình luận đã được xóa');
    }

    /**
     * Lấy bình luận đã duyệt của sản phẩm.
     */
    public function getReviews($productId)
    {
        $reviews = ProductReview::getApprovedReviews($productId);

        return response()->json([
            'status' => 'success',
            'data' => $reviews
        ]);
    }

    /**
     * Hiển thị các bình luận chờ duyệt.
     */
    public function pendingReviews()
    {
        $reviews = ProductReview::getPendingReviews();
        return view('reviews.pending', compact('reviews'));
    }

    /**
     * Hiển thị các bình luận đã duyệt.
     */
    public function approvedReviews()
    {
        $reviews = ProductReview::getApprovedReview();
        return view('reviews.approved', compact('reviews'));
    }

    /**
     * Duyệt bình luận.
     */
    public function approve($id)
    {
        if (ProductReview::approveReview($id)) {
            return redirect()->back()->with('success', 'Bình luận đã được duyệt');
        }
        return redirect()->back()->with('error', 'Có lỗi xảy ra khi duyệt bình luận');
    }

    /**
     * Từ chối bình luận.
     */
    public function reject($id)
    {
        if (ProductReview::rejectReview($id)) {
            return redirect()->back()->with('success', 'Bình luận đã bị từ chối');
        }
        return redirect()->back()->with('error', 'Có lỗi xảy ra khi từ chối bình luận');
    }
}
