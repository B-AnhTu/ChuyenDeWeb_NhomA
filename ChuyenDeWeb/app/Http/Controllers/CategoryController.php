<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\Category\CategoryService;
use App\Services\Category\CategorySortAndSearch;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Support\Facades\Session; 


class CategoryController extends Controller
{
    protected $categoryService, $categorySortAndSearch;

    public function __construct(CategoryService $categoryService, CategorySortAndSearch $categorySortAndSearch)
    {
        $this->categoryService = $categoryService;
        $this->categorySortAndSearch = $categorySortAndSearch;
    }

    // Danh sách danh mục
    public function list(Request $request)
    {
        // Lấy từ khóa tìm kiếm và lựa chọn sắp xếp từ request
        $searchTerm = $request->input('query');
        $sortBy = $request->input('sort_by');

        // Khởi tạo truy vấn
        $query = Category::query(); // Tạo một truy vấn mới

        // Nếu có tìm kiếm, thực hiện tìm kiếm
        if ($searchTerm) {
            $query= $this->categorySortAndSearch->searchCategories($searchTerm);
        }

        // Nếu có sắp xếp, thực hiện sắp xếp
        if ($sortBy) {
            $query = $this->categorySortAndSearch->sortCategories($query, $sortBy); // Gọi phương thức sắp xếp từ service
        }

        else {
            // Nếu không có sắp xếp, sắp xếp theo ngày tạo mới
            $query = $query->orderBy('created_at', 'desc');
        }

        // Phân trang danh mục
        $categories = $query->paginate(5);

        return view('categoryAdmin', [
            'categories' => $categories, // Phân trang
            'filters' => [
                'searchTerm' => $searchTerm,
                'sort_by' => $sortBy,
            ],
        ]);
        
    }

    // Hiển thị trang tạo danh mục
    public function create()
    {
        return view('categoryCreate');
    }

    // Tạo mới danh mục
    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());
        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    // Hiển thị chi tiết danh mục
    public function show($slug)
    {
        $category = $this->categoryService->getCategoryBySlug($slug);
        if (!$category) {
            Session::flash('error', 'Danh mục không tồn tại');
            return redirect()->route('category.index')->withInput();
        }
        return view('categoryShow', compact('category'));
    }

    // Hiển thị form cập nhật danh mục
    public function edit($slug)
    {
        $category = $this->categoryService->getCategoryBySlug($slug);
        if (!$category) {
            Session::flash('error', 'Danh mục không tồn tại');
            return redirect()->route('category.index')->withInput();
        }
        return view('categoryUpdate', compact('category'));
    }

    // Cập nhật danh mục
    public function update(UpdateCategoryRequest $request, $slug)
    {
        try {
            // Tìm category theo slug
            $category = $this->categoryService->getCategoryBySlug($slug);
            // Kiểm tra nếu category không tồn tại
            if (!$category) {
                Session::flash('error', 'Danh mục không tồn tại');
                return redirect()->route('categoryAdmin.index')->withInput();
            }
    
            // Lưu dữ liệu đã validated
            $validatedData = $request->validated();
    
            // Gọi service để cập nhật category
            $this->categoryService->updateCategory($category, $validatedData);
            
            // Thông báo thành công
            Session::flash('success', 'Cập nhật danh mục thành công.');
            return redirect()->route('category.index')->with('success', 'Cập nhật danh mục thành công.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('category.edit', ['slug' => $slug])->withInput(); // Chuyển hướng về trang cập nhật
        }
        
    }

    // Xóa danh mục
    public function destroy($slug)
    {
        // Tìm category theo slug
        $category = $this->categoryService->getCategoryBySlug($slug);
        // Kiểm tra nếu category không tồn tại
        if (!$category) {
            Session::flash('error', 'Danh mục không tồn tại');
            return redirect()->route('category.index')->withInput();
        }
        try {
            // Gọi service để xóa category
            $this->categoryService->deleteCategory($slug);
            
            // Thông báo thành công
            return redirect()->route('category.index')->with('success', 'Xóa danh mục thành công.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('category.index')->withInput(); 
        }
        
    }
    
    
}
