<?php
namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Services\SlugService;
use Illuminate\Support\Facades\DB;


class CategoryService
{
    protected $slugService; // Khai báo thuộc tính

    // Constructor để nhận SlugService thông qua dependency injection
    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService; // Khởi tạo thuộc tính slugService
    }
    // Lấy danh sách danh mục có phân trang
    public function getAllCategories()
    {
        return Category::getAllCategory();
    }
    /**
     * 
     * Thêm danh mục
     * @param mixed $validatedData
     * @return Category|\Illuminate\Database\Eloquent\Model
     */
    public function createCategory($validatedData)
    {
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/category'), $filename);
            $validatedData['image'] = $filename;
        }

        $validatedData['slug'] = $this->slugService->slugify($validatedData['category_name']);
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        return Category::createCategory($validatedData);
    }
    /**
     * Lấy danh mục theo slug
     */
    public function getCategoryBySlug($categorySlug){
        return Category::getCategoryBySlug($categorySlug);
    }
    /**
     * Sửa danh mục ( có kiểm tra lỗi bảo mật Optimistic Locking)
     */
    public function updateCategory($category, $validatedData)
    {
        // Làm mới slug dựa trên category_name
        $validatedData['slug'] = $this->slugService->slugify($validatedData['category_name']);

        return $category->updateWithConflictCheck($validatedData);
    }
    /**
     * Xóa danh mục
     */
    public function deleteCategory($slug)
    {
        // Tìm Category theo slug
        $category = Category::getCategoryBySlug($slug);

        // Kiểm tra xem Category có tồn tại không
        if (!$category) {
            throw new \Exception('Không tìm thấy danh mục. Có thể danh mục đã bị chỉnh sửa hoặc xóa bởi người dùng khác.');
        }

        // Thực hiện xóa category
        try {
            Category::deleteCategoryBySlug($category->slug);

        } catch (\Exception $e) {
            // Xử lý lỗi trong trường hợp xóa không thành công
            throw new \Exception('Xảy ra lỗi trong khi cố gắng xóa danh mục: ' . $e->getMessage());
        }

        // Trả về true nếu xóa thành công
        return true;
    }
}
