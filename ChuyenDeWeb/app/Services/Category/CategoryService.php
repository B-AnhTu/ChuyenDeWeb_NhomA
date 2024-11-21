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

        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        // Tạo người dùng mới
        $category = Category::createCategory($validatedData);

        // Tạo slug cho người dùng sau khi đã tạo
        $category->slug = Category::generateUniqueSlug($category->category_name, $category->category_id);
        $category->save();

        return $category; // Trả về người dùng đã tạo
    }
    /**
     * Lấy danh mục theo slug
     */
    public function getCategoryBySlug($slug){
        $categoryId = Category::decodeSlug($slug); // Giải mã slug để lấy ID sản phẩm
        return Category::find($categoryId);
    }
    /**
     * Sửa danh mục ( có kiểm tra lỗi bảo mật Optimistic Locking)
     */
    public function updateCategory($category, $validatedData)
    {
        // Làm mới slug dựa trên category_name

        $validatedData['updated_at'] = now();

        // Cập nhật thông tin người dùng
        $category->updateWithConflictCheck($validatedData);

        // Nếu fullname đã thay đổi, tạo lại slug
        if ($category->category_name !== $validatedData['category_name']) {
            $category->slug = Category::generateUniqueSlug($validatedData['category_name'], $validatedData['category_id']);
        }

        $category->save();

        return $category; // Trả về người dùng đã cập nhật
    }
    /**
     * Xóa danh mục
     */
    public function deleteCategory($slug)
    {
        return Category::deleteCategoryBySlug($slug);
    }
}
