<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductLike;
use App\Models\Product; // Thêm model Product
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductLikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vui lòng đăng nhập để thực hiện chức năng này'], 401);
        }

        $userId = Auth::id();
        $productId = $request->input('product_id');

        // Kiểm tra sản phẩm có tồn tại
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        // Kiểm tra xem sản phẩm đã được thích bởi người dùng chưa
        $existingLike = ProductLike::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingLike) {
            // Nếu đã thích, xóa khỏi bảng yêu thích
            $existingLike->delete();
            return response()->json(['message' => 'Đã bỏ yêu thích sản phẩm']);
        } else {
            // Nếu chưa thích, thêm vào bảng yêu thích
            ProductLike::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return response()->json(['message' => 'Đã thêm sản phẩm vào yêu thích']);
        }
    }

    public function wishlist()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để xem sản phẩm yêu thích');
        }

        $userId = Auth::id();

        // Lấy danh sách sản phẩm yêu thích của người dùng
        $likedProducts = ProductLike::where('user_id', $userId)
            ->with('product')
            ->get();

        return view('wishlist', compact('likedProducts'));
    }
}
