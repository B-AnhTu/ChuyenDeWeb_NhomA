<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Manufacturer;
use App\Models\Category;
use App\Models\ProductLike;
use App\Rules\SingleSpaceOnly;
use App\Rules\NoSpecialCharacters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(8);
        $manufacturers = Manufacturer::all();
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage()
            ]);
        }

        // Kiểm tra xem người dùng đã thích sản phẩm nào
        $likedProductIds = Auth::check()
            ? ProductLike::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        $posts = Blog::orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        return view('index', compact('products', 'manufacturers', 'categories', 'posts' , 'likedProductIds'));
    }


    // lọc sản phẩm theo nhà sản xuất
    public function filter(Request $request)
    {
        // Kiểm tra xem manufacturer_id có được gửi từ request hay không
        if ($request->has('manufacturer_id')) {
            $products = Product::where('manufacturer_id', $request->manufacturer_id)
                ->orderBy('created_at', 'desc')
                ->paginate(8); // Phân trang với 8 sản phẩm một lần

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

    // lọc sản phẩm theo loại sản phẩm
    public function filterByCategory(Request $request)
    {
        // Kiểm tra xem category_id có được gửi từ request hay không
        if ($request->has('category_id')) {
            $products = Product::where('category_id', $request->category_id)
                ->orderBy('created_at', 'desc')
                ->paginate(8); // Phân trang với 8 sản phẩm một lần

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


    // tìm kiếm sản phẩm theo nhà sản xuất
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->has('manufacturer_id') && $request->manufacturer_id) {
            $query->where('manufacturer_id', $request->manufacturer_id);
        }

        if ($request->has('keyword') && $request->keyword) {
            $query->where('product_name', 'like', '%' . $request->keyword . '%');
        }

        $products = $query->paginate(8);

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'message' => $products->isEmpty() ? 'Không tìm thấy sản phẩm nào.' : null
            ]);
        }
        return view('index', compact('products', 'manufacturers'));
    }


    // sắp xếp
    public function sort(Request $request)
    {
        // Khởi tạo truy vấn sản phẩm
        $query = Product::query();

        // Lọc theo nhà sản xuất nếu có
        if ($request->has('manufacturer_id') && $request->manufacturer_id) {
            $query->where('manufacturer_id', $request->manufacturer_id);
        }

        // Lọc theo từ khóa nếu có
        if ($request->has('keyword') && $request->keyword) {
            $query->where('product_name', 'like', '%' . $request->keyword . '%');
        }

        // Sắp xếp sản phẩm dựa trên yêu cầu
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('product_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('product_name', 'desc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                default:
                    // Mặc định không sắp xếp
                    break;
            }
        } else {
            // Nếu không có yêu cầu sắp xếp, sắp xếp theo ngày tạo
            $query->orderBy('created_at', 'desc');
        }

        // Phân trang kết quả
        $products = $query->paginate(8);

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
        $product = Product::where('slug', $slug)->firstOrFail();

        // tăng số lượt xem 
        $product->increment('product_view');
        // Trả về view chi tiết sản phẩm và truyền dữ liệu sản phẩm
        return view('productDetail', compact('product'));
    }




    // Hiển thị danh sách sản phẩm trong admin
    public function list()
    {
        $products = Product::orderBy('created_at', 'asc')->paginate(5);
        return view('productAdmin', compact('products'));
    }
    // Hiển thị form tạo sản phẩm trong admin
    public function create()
    {
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('productCreate', compact('manufacturers', 'categories'));
    }
    // Lưu sản phẩm mới vào database
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:50', new NoSpecialCharacters],
            'price' => 'required|numeric|min:0',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:5120',
            'description' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'manufacturer_id' => 'required',
            'category_id' => 'required',
        ], [
            'product_name.required' => 'Tên sản phẩm không được để trống',
            'product_name.max' => 'Tên sản phẩm không được quá 50 ký tự',
            'price.required' => 'Giá sản phẩm không được để trống',
            'price.numeric' => 'Giá sản phẩm bắt buộc phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn 0',
            'image.required' => 'Hình ảnh không được để trống',
            'image.mimes' => 'Hình ảnh phải có đuôi .jpeg, .jpg, .png, .gif',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'description.required' => 'Mô tả không được để trống',
            'stock_quantity.required' => 'Số lượng hàng tồn kho không được để trống',
            'stock_quantity.integer' => 'Số lượng hàng tồn kho phải là số nguyên',
            'stock_quantity.min' => 'Số lượng hàng tồn kho phải lớn hơn 0',
            'manufacturer_id.required' => 'Nhà sản xuất không được để trống',
            'manufacturer_id.exists' => 'Nhà sản xuất không tồn tại',
            'category_id.required' => 'Danh mục không được để trống',
            'category_id.exists' => 'Danh mục không tồn tại',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/products'), $filename);

            // Cập nhật ảnh mới trong database
            $data['image'] = $filename;
        }

        $product = Product::create([
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'image' => $data['image'],
            'description' => $data['description'],
            'stock_quantity' => $data['stock_quantity'],
            'manufacturer_id' => $data['manufacturer_id'],
            'category_id' => $data['category_id'],
        ]);
        $product->save();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được tạo thành công');
    }
    // Hiển thị chi tiết sản phẩm
    public function show(Request $request, string $product_id)
    {
        $product = Product::findOrFail($product_id);
        if (!$product) {
            return redirect()->route('productAdmin.index')->with('error', 'Sản phẩm không tồn tại');
        }
        return view('productShow', compact('product'));
    }
    // Hiển thị form cập nhật sản phẩm trong admin
    public function edit($product_id)
    {
        $product = Product::find($product_id);
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('productUpdate', compact('product', 'manufacturers', 'categories'));
    }
    // Cập nhật sản phẩm trong database
    public function update(Request $request, $product_id)
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:50'],
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
            'description' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'manufacturer_id' => 'required',
            'category_id' => 'required',
        ], [
            'product_name.required' => 'Vui lòng nhập tên sản phẩm',
            'product_name.max' => 'Tên sản phẩm không được quá 50 ký tự',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'price.numeric' => 'Giá sản phẩm bắt buộc phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn 0',
            'image.mimes' => 'Hình ảnh phải có đuôi .jpeg, .jpg, .png, .gif',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'description.required' => 'Vui lòng nhập chi tiết sản phẩm',
            'stock_quantity.required' => 'Vui lòng nhập số lượng hàng tồn kho',
            'stock_quantity.integer' => 'Số lượng hàng tồn kho phải là số nguyên',
            'stock_quantity.min' => 'Số lượng hàng tồn kho phải lớn hơn 0',
            'manufacturer_id.required' => 'Nhà sản xuất không được để trống',
            'category_id.required' => 'Danh mục không được để trống',
        ]);

        $product = Product::find($product_id);

        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại.');
        }

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/products'), $filename);

            // Delete old image if exists
            if ($product->image && file_exists(public_path('img/products/' . $product->image))) {
                unlink(public_path('img/products/' . $product->image));
            }

            // Update with new image
            $product->image = $filename;
        }

        // Update other fields
        $product->product_name = $request->input('product_name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->manufacturer_id = $request->input('manufacturer_id');
        $product->category_id = $request->input('category_id');
        $product->save();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được cập nhật thành công');
    }
    // Xóa sản phẩm trong database
    public function destroy($product_id)
    {
        // Kiểm tra xem sản phẩm có tồn tại không
        $product = Product::findOrFail($product_id);
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại.');
        }
        // Delete image if exists
        if ($product->image && file_exists(public_path('img/products/' . $product->image))) {
            unlink(public_path('img/products/' . $product->image));
        }
        // Thực hiện xóa sản phẩm
        try {
            $product->delete();
            return redirect()->route('product.index')->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi khi xóa không thành công
            return redirect()->route('product.index')->with('error', 'Xóa sản phẩm không thành công.');
        }
    }
}
