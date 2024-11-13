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
use Illuminate\Support\Str;
use App\Services\SlugService;


class ProductController extends Controller
{
    protected $slugService; // Khai báo thuộc tính slugService

    public function __construct(SlugService $slugService) // Constructor
    {
        $this->slugService = $slugService; // Khởi tạo slugService
    }
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(6);
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
        return view('product', compact('products', 'manufacturers', 'posts', 'likedProductIds'));
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


    // tìm kiếm sản phẩm theo nhà sản xuất
    public function search(Request $request)
    {
        $query = Product::query();

        // Xử lý tìm kiếm full-text
        if ($request->has('keyword') && $request->keyword) {
            $searchTerm = $request->keyword;

            // Chuẩn bị từ khóa tìm kiếm
            $searchWords = explode(' ', $searchTerm);
            $searchWords = array_filter($searchWords, function ($word) {
                return strlen($word) >= 2;
            });

            if (!empty($searchWords)) {
                $searchQuery = '+' . implode('* +', $searchWords) . '*';

                $query->whereRaw("MATCH(product_name, description) AGAINST(? IN BOOLEAN MODE)", [$searchQuery]);
            }
        }

        // Lọc theo nhà sản xuất
        if ($request->has('manufacturer_id') && $request->manufacturer_id) {
            $query->where('manufacturer_id', $request->manufacturer_id);
        }

        // Sắp xếp kết quả theo độ phù hợp và thêm các thông tin liên quan
        $query->select('product.*')
            ->with(['category', 'manufacturer'])
            ->when($request->has('keyword') && $request->keyword, function ($q) use ($request) {
                $searchTerm = $request->keyword;
                $searchWords = explode(' ', $searchTerm);
                $searchWords = array_filter($searchWords, function ($word) {
                    return strlen($word) >= 2;
                });
                if (!empty($searchWords)) {
                    $searchQuery = '+' . implode('* +', $searchWords) . '*';
                    $q->selectRaw("MATCH(product_name, description) AGAINST(? IN BOOLEAN MODE) as relevance", [$searchQuery]);
                    $q->orderBy('relevance', 'desc');
                }
            })
            ->orderBy('product_view', 'desc') // Sắp xếp thêm theo lượt xem
            ->orderBy('sold_quantity', 'desc'); // Và theo số lượng đã bán

        $products = $query->paginate(6);

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

        return view('product', compact('products', 'manufacturers', 'categories'));
    }

    // Hiển thị danh sách sản phẩm trong admin
    public function list()
    {
        $products = Product::paginate(5);
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
            'product_name' => ['required', 'string', 'max:50', new NoSpecialCharacters, new SingleSpaceOnly],
            'price' => 'required|numeric|min:0',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:5120',
            'description' => ['required', new NoSpecialCharacters, new SingleSpaceOnly],
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

        //Tạo slug từ tên sản phẩm
        $data['slug'] = $this->slugService->slugify($data['product_name']); // Sử dụng hàm slugify để tạo slug

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
            'slug' => $data['slug'],
        ]);
        $product->save();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được tạo thành công');
    }
    // Hiển thị chi tiết sản phẩm
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại');
        }
        return view('productShow', compact('product'));
    }
    // Hiển thị form cập nhật sản phẩm trong admin
    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại');
        }
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('productUpdate', compact('product', 'manufacturers', 'categories'));
    }
    // Cập nhật sản phẩm trong database
    public function update(Request $request, $slug)
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:50', new NoSpecialCharacters, new SingleSpaceOnly],
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
            'description' => ['required', new NoSpecialCharacters, new SingleSpaceOnly],
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

        $product = Product::where('slug', $slug)->first();

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

        // Cập nhật các trường dữ liệu của product
        $product->product_name = $request->input('product_name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->manufacturer_id = $request->input('manufacturer_id');
        $product->category_id = $request->input('category_id');
        // Tạo slug mới nếu tên sản phẩm thay đổi
        $product->slug = $this->slugService->slugify($request->input('product_name')); // Sử dụng hàm slugify để tạo slug
        $product->save();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được cập nhật thành công');
    }
    // Xóa sản phẩm trong database
    public function destroy($slug)
    {
        // Kiểm tra xem sản phẩm có tồn tại không
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại.');
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
    // Sắp xếp theo tên, ngày cập nhật
    public function sortProducts(Request $request)
    {
        $query = Product::query();

        // Sắp xếp theo yêu cầu
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('product_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('product_name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'views_asc':
                    $query->orderBy('product_view', 'asc');
                    break;
                case 'views_desc':
                    $query->orderBy('product_view', 'desc');
                    break;
                case 'purchases_asc':
                    $query->orderBy('sold_quantity', 'asc');
                    break;
                case 'purchases_desc':
                    $query->orderBy('sold_quantity', 'desc');
                    break;
                case 'stock_asc':
                    $query->orderBy('stock_quantity', 'asc');
                    break;
                case 'stock_desc':
                    $query->orderBy('stock_quantity', 'desc');
                    break;
                case 'updated_at_asc':
                    $query->orderBy('updated_at', 'asc');
                    break;
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                default:
                    // Mặc định không sắp xếp
                    break;
            }
        }

        $products = $query->paginate(5); // Phân trang

        return view('productAdmin', compact('products'));
    }

    // Tìm kiếm sản phẩm theo tên, chi tiết theo fulltext search
    public function searchProducts(Request $request)
    {
        $query = Product::query();
        $searchTerm = $request->input('query');

        // Tìm kiếm full-text ưu tiên theo product_name trước, sau đó là description
        if ($searchTerm) {
            $searchWords = explode(' ', $searchTerm);
            $searchWords = array_filter($searchWords, function ($word) {
                return strlen($word) >= 2;
            });

            if (!empty($searchWords)) {
                // Tạo truy vấn fulltext
                $searchQuery = '+' . implode('* +', $searchWords) . '*';
                $query->whereRaw("MATCH(product_name, description) AGAINST(? IN BOOLEAN MODE)", [$searchQuery]);
            }
        }

        // Sắp xếp theo thứ tự ưu tiên và phân trang
        $products = $query->orderByRaw("CASE WHEN product_name LIKE ? THEN 1 ELSE 2 END", ["%$searchTerm%"])
            ->paginate(5);

        // Truyền dữ liệu tìm kiếm vào view
        return view('productAdmin', [
            'products' => $products,
            'searchTerm' => $searchTerm,
        ]);
    }
    // Xóa tạm thời sản phẩm khỏi database
    public function trashed()
    {
        // Lấy danh sách các sản phẩm đã xóa tạm thời
        $trashedProducts = Product::onlyTrashed()->paginate(5);

        return view('trashed', compact('trashedProducts'));
    }
    // Khôi phục sản phẩm đã xóa
    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if ($product) {
            $product->restore();
            return redirect()->route('product.trashed')->with('success', 'Sản phẩm đã được khôi phục.');
        }

        return redirect()->route('product.trashed')->with('error', 'Sản phẩm không tồn tại.');
    }

    // Xóa vĩnh viễn sản phẩm
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if ($product) {
            // Delete image if exists
            if ($product->image && file_exists(public_path('img/products/' . $product->image))) {
                unlink(public_path('img/products/' . $product->image));
            }
            // Xóa sản phẩm trong database
            $product->forceDelete();
            return redirect()->route('product.trashed')->with('success', 'Sản phẩm đã được xóa vĩnh viễn.');
        }

        return redirect()->route('product.trashed')->with('error', 'Sản phẩm không tồn tại.');
    }
    
}
