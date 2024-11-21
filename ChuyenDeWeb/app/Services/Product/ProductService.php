<?php
namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\SlugService;


class ProductService
{
    protected $slugService; // Khai báo thuộc tính

    // Constructor để nhận SlugService thông qua dependency injection
    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService; // Khởi tạo thuộc tính slugService
    }
    // Lấy danh sách danh mục có phân trang
    public function getAllProducts()
    {
        return Product::getAllProducts();
    }
    /**
     * Lấy sản phẩm theo slug
     */
    public function getProductBySlug($slug){
        return Product::getProductBySlug($slug);
    }
    /**
     * 
     * Thêm sản phẩm
     */
    public function createProduct($validatedData)
    {
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/products'), $filename);
            $validatedData['image'] = $filename;
        }

        $validatedData['slug'] = $this->slugService->slugify($validatedData['product_name']);
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();
        
        $product = new Product();
        return $product->createProduct($validatedData); 
    }
    /**
     * Sửa danh mục ( có kiểm tra lỗi bảo mật Optimistic Locking)
     */
    public function updateProduct($product, $validatedData)
    {
        // Làm mới slug dựa trên Product_name
        $validatedData['slug'] = $this->slugService->slugify($validatedData['product_name']);

        return $product->updateWithConflictCheck($validatedData);
    }
    /**
     * Xóa sản phẩm
     */
    public function deleteProduct($slug)
    {
        // Tìm Product theo slug
        $product = Product::getProductBySlug($slug);

        // Kiểm tra xem Product có tồn tại không
        if (!$product) {
            throw new \Exception('Product not found. It may have already been deleted.');
        }

        // Thực hiện xóa Product
        try {
            Product::deleteProductBySlug($product->slug);
        } catch (\Exception $e) {
            // Xử lý lỗi trong trường hợp xóa không thành công
            throw new \Exception('An error occurred while trying to delete the Product: ' . $e->getMessage());
        }

        // Trả về true nếu xóa thành công
        return true;

    }
    /**
     * Khôi phục sản phẩm
     */
    public function restoreProduct($slug){
        return Product::restoreProduct($slug);
    }
    /**
     * Xóa sản phẩm đã xóa
     */
    public function forceDeleteProduct($slug){
        return Product::forceDeleteProduct($slug);
    }
}
