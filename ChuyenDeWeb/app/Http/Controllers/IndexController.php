<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::getAllProducts();
        $manufacturers = Manufacturer::all();
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage()
            ]);
        }

        $likedProductIds = Auth::check()
            ? ProductLike::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        $posts = Blog::orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        return view('index', compact('products', 'manufacturers', 'categories', 'posts', 'likedProductIds'));
    }



    // lọc sản phẩm theo nhà sản xuất
    public function filter(Request $request)
    {
        if ($request->has('manufacturer_id')) {
            $products = Product::filterByManufacturer($request->manufacturer_id); // Gọi từ model
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage()
            ]);
        }

        return response()->json([
            'data' => []  // Trả về mảng rỗng nếu không có sản phẩm
        ]);
    }


    // lọc sản phẩm theo loại sản phẩm
    public function filterByCategory(Request $request)
    {
        if ($request->has('category_id')) {
            $products = Product::filterByCategory($request->category_id); // Gọi từ model
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total()
            ]);
        }

        return response()->json([
            'data' => [],
            'current_page' => 1,
            'last_page' => 1,
            'total' => 0
        ]);
    }



    // tìm kiếm sản phẩm theo nhà sản xuất
    public function search(Request $request)
    {
        // Lấy danh sách manufacturers trước khi tìm kiếm
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        $products = Product::searchProducts($request->keyword, $request->manufacturer_id);
        $likedProductIds = Auth::check()
        ? ProductLike::where('user_id', Auth::id())->pluck('product_id')->toArray()
        : [];

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'message' => $products->isEmpty() ? 'Không tìm thấy sản phẩm nào.' : null
            ]);
        }

        return view('index', compact('products', 'manufacturers', 'categories', 'likedProductIds'));
    }



    // sắp xếp
    public function sort(Request $request)
    {
        $products = Product::sortProducts($request->sort_by, $request->manufacturer_id, $request->keyword);

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'message' => $products->isEmpty() ? 'Không tìm thấy sản phẩm nào.' : null
            ]);
        }

        return view('index', compact('products', 'manufacturers', 'categories'));
    }



    // hiển thị chi tiết sản phẩm
    public function showProductDetail($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->first();

        // Nếu sản phẩm không tồn tại
        if (!$product) {
            return view('404')->with('error', 'Sản phẩm không tồn tại');
        }

        // tăng số lượt xem
        $product->increment('product_view');

        return view('productDetail', compact('product'));
    }
}
