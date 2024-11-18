<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\NewProductNotification;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Manufacturer;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\ProductLike;
use App\Rules\SingleSpaceOnly;
use App\Rules\NoSpecialCharacters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\Product\ProductService;
use App\Services\Product\ProductSortAndSearch;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    protected $productService, $productSortAndSearch;

    public function __construct(ProductService $productService, ProductSortAndSearch $productSortAndSearch)
    {
        $this->productService = $productService;
        $this->productSortAndSearch = $productSortAndSearch;
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
    public function list(Request $request)
    {
        // Lấy từ khóa tìm kiếm và lựa chọn sắp xếp từ request
        $searchTerm = $request->input('query');
        $sortBy = $request->input('sort_by');

        // Khởi tạo truy vấn
        $query = Product::query(); // Tạo một truy vấn mới

        // Nếu có tìm kiếm, thực hiện tìm kiếm
        if ($searchTerm) {
            $query = $this->productSortAndSearch->searchProducts($searchTerm);
        }

        // Nếu có sắp xếp, thực hiện sắp xếp
        if ($sortBy) {
            $query = $this->productSortAndSearch->sortProducts($query, $sortBy); // Gọi phương thức sắp xếp từ service
        }

        // Phân trang danh mục
        $products = $query->paginate(5);

        return view('productAdmin', [
            'products' => $products, // Phân trang
            'filters' => [
                'searchTerm' => $searchTerm,
                'sort_by' => $sortBy,
            ],
        ]);
    }
    // Hiển thị form tạo sản phẩm trong admin
    public function create()
    {
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('productCreate', compact('manufacturers', 'categories'));
    }
    // Lưu sản phẩm mới vào database
    public function store(StoreProductRequest $request)
    {
        // Xử lý lưu sản phẩm mới
        $this->productService->createProduct($request->validated());
        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được tạo thành công');
    }
    // Hiển thị chi tiết sản phẩm
    public function show($slug)
    {
        $product = $this->productService->getProductBySlug($slug);
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại');
        }
        return view('productShow', compact('product'));
    }
    // Hiển thị form cập nhật sản phẩm trong admin
    public function edit($slug)
    {
        $product = $this->productService->getProductBySlug($slug);

        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại');
        }
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('productUpdate', compact('product', 'manufacturers', 'categories'));
    }
    // Cập nhật sản phẩm trong database
    public function update(UpdateProductRequest $request, $slug)
    {
        try {
            // Tìm product theo slug
            $product = $this->productService->getProductBySlug($slug);
            // Kiểm tra nếu product không tồn tại
            if (!$product) {
                Session::flash('error', 'Product not found. It may have been deleted or modified by another user.');
                return redirect()->route('productAdmin.index')->withInput();
            }
    
            // Lưu dữ liệu đã validated từ request
            $validatedData = $request->validated();
    
            // Gọi service để cập nhật product
            $this->productService->updateProduct($product, $validatedData);
    
            // Thông báo thành công
            Session::flash('success', 'Product updated successfully.');
            return redirect()->route('product.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('product.edit', ['slug' => $slug])->withInput(); // Chuyển hướng về trang cập nhật
        }
    }
    // Xóa sản phẩm trong database
    public function destroy($slug)
    {
        try {
            $this->productService->deleteProduct($slug);

            //Thông báo thành công
            return redirect()->route('product.index')->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error',$e->getMessage());
            return redirect()->route('product.index')->withInput();
        }
    }
    // Xóa tạm thời sản phẩm khỏi database
    public function trashed()
    {
        // Lấy danh sách các sản phẩm đã xóa tạm thời
        $trashedProducts = Product::onlyTrashed()->paginate(5);

        return view('trashed', compact('trashedProducts'));
    }
    // Khôi phục sản phẩm đã xóa
    public function restore($slug)
    {
        try {
            $this->productService->restoreProduct($slug);
            // Thông báo thành công
            return redirect()->route('product.trashed')->with('success', 'Sản phẩm đã được khôi phục.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error',$e->getMessage());
            return redirect()->route('product.trashed')->withInput();
        }
        
    }

    // Xóa vĩnh viễn sản phẩm
    public function forceDelete($slug)
    {
        try {
            $this->productService->forceDeleteProduct($slug);
            // Thông báo thành công
            return redirect()->route('product.trashed')->with('success', 'Sản phẩm đã được xóa vĩnh viễn.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error',$e->getMessage());
            return redirect()->route('product.trashed')->withInput();
        }
    }
}
