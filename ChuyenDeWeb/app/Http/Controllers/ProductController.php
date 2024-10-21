<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(6);
        $manufacturers = Manufacturer::all();

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage()
            ]);
        }

        return view('index', compact('products', 'manufacturers'));
    }


    // lọc sản phẩm theo nhà sản xuất
    public function filter(Request $request)
    {
        // Kiểm tra xem manufacturer_id có được gửi từ request hay không
        if ($request->has('manufacturer_id')) {
            $products = Product::where('manufacturer_id', $request->manufacturer_id)
                ->orderBy('created_at', 'desc')
                ->paginate(6); // Phân trang với 6 sản phẩm một lần

            // Trả về JSON chứa sản phẩm và thông tin phân trang
            return response()->json([
                'data' => $products->items(),  // Trả về danh sách sản phẩm
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage()
            ]);
        }

        return response()->json([
            'data' => []  // Trả về mảng rỗng nếu không có sản phẩm
        ]);
    }
}
