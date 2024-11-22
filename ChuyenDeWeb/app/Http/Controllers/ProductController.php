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
use Illuminate\Support\Facades\Auth;
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
        $products = Product::getAllProductsViewProduct();
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
        return view('product', compact('products', 'manufacturers', 'posts', 'likedProductIds'));
    }


    // lọc sản phẩm theo nhà sản xuất
    public function filter(Request $request)
    {
        if ($request->has('manufacturer_id')) {
            $products = Product::filterByManufacturerViewProduct($request->manufacturer_id); // Gọi từ model
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


    // tìm kiếm sản phẩm theo nhà sản xuất
    public function search(Request $request)
    {
        // Lấy danh sách manufacturers trước khi tìm kiếm
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        $products = Product::searchProductsViewProduct($request->keyword, $request->manufacturer_id);
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

        return view('product', compact('products', 'manufacturers', 'categories', 'likedProductIds'));
    }

    // sắp xếp
    public function sort(Request $request)
    {
        $products = Product::sortProductsViewProduct($request->sort_by, $request->manufacturer_id, $request->keyword);

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

            // Kiểm tra nếu sản phẩm không tồn tại
            if (!$product) {
                Session::flash('error', 'Sản phẩm không tồn tại');
                return redirect()->route('productAdmin.index')->withInput();
            }

            // Lưu dữ liệu đã validated từ request
            $validatedData = $request->validated();

            // Gọi service để cập nhật product
            $this->productService->updateProduct($product, $validatedData);

            // Thông báo thành công
            Session::flash('success', 'Cập nhật sản phẩm thành công.');
            return redirect()->route('product.index')->with('success', 'Cập nhật sản phẩm thành công.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('product.index')->withInput(); // Chuyển hướng về trang cập nhật
        }
    }
    // Xóa sản phẩm trong database
    public function destroy($slug)
    {
        try {
            $this->productService->deleteProduct($slug);

            //Thông báo thành công
            return redirect()->route('product.index')->with('success', 'Xóa sản phẩm thành công');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('product.index')->withInput();
        }
    }
    // Xóa tạm thời sản phẩm khỏi database
    public function trashed()
    {
        // Lấy danh sách các sản phẩm đã xóa tạm thời
        $trashedProducts = $this->productService->getDeletedProducts();

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
            Session::flash('error', $e->getMessage());
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
            Session::flash('error', $e->getMessage());
            return redirect()->route('product.trashed')->withInput();
        }
    }
}
