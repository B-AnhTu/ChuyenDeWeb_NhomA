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
    // Lấy danh sách sản phẩm đã bị xóa tạm thời
    public function getDeletedProducts(){
        return Product::getDeletedProducts();
    }
    /**
     * Lấy sản phẩm theo slug
     */
    public function getProductBySlug($slug){
        $productId = Product::decodeSlug($slug); // Giải mã slug để lấy ID sản phẩm
        return Product::getProductById($productId); // Tìm sản phẩm theo ID
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

        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        // Tạo người dùng mới
        $product = Product::createProduct($validatedData);

        // Tạo slug cho người dùng sau khi đã tạo
        $product->slug = Product::generateUniqueSlug($product->product_name, $product->product_id);
        $product->save();

        return $product; // Trả về người dùng đã tạo
    }
    /**
     * Sửa danh mục ( có kiểm tra lỗi bảo mật Optimistic Locking)
     */
    public function updateProduct($product, $validatedData)
    {
        // Kiểm tra xem image có tồn tại trong validated data
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/profile-picture'), $filename);
            $validatedData['image'] = $filename;
        }

        $validatedData['updated_at'] = now();

        // Cập nhật thông tin người dùng
        $product->updateWithConflictCheck($validatedData);

        // Nếu fullname đã thay đổi, tạo lại slug
        if ($product->product_name !== $validatedData['product_name']) {
            $product->slug = Product::generateUniqueSlug($validatedData['product_name'], $validatedData['product_id']);
        }

        $product->save();

        return $product; // Trả về người dùng đã cập nhật
    }
    /**
     * Xóa sản phẩm
     */
    public function deleteProduct($slug)
    {
        return Product::deleteProductBySlug($slug);
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
