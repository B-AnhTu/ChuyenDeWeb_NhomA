<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;
use Illuminate\Http\Request;
use App\Services\SlugService;

class CategoryController extends Controller
{
    protected $slugService; // Khai báo thuộc tính slugService

    public function __construct(SlugService $slugService) // Constructor
    {
        $this->slugService = $slugService; // Khởi tạo slugService
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data_cate = Category::getAllCate();
        return view('categoryAdmin', ['data_cate' => $data_cate]);
    }
    public function list(){
        $categories = Category::orderBy('category_id', 'asc')->paginate(5);
        return view('categoryAdmin', ['categories' => $categories]);
    }

    /**
     * Hiển thị trang tạo danh mục
     */
    public function create()
    {
        return view('categoryCreate');
    }

    /**
     * Tạo danh mục mới
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'category_name' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters],
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'category_name.required' => 'Vui lòng nhập tên danh mục',
            'category_name.max' => 'Tên danh mục không được quá 50 ký tự',
            'image.required' => 'Vui lòng chọn hình ảnh để tải lên',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $data = $request->all();

        $data['slug'] = $this->slugService->slugify($data['category_name']); // Sử dụng hàm slugify để tạo slug

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/category'), $filename);

            // Cập nhật ảnh mới trong database
            $data['image'] = $filename;
        }

        $category = Category::create([
            'category_name' => $data['category_name'],
            'image' => $data['image'],
            'slug' => $data['slug'],
        ]);
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    /**
     * Hiển thị chi tiết danh mục
     */
    public function show($slug)
    {
        //Tìm id của danh mục cần xem
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục không tồn tại');
        }
        return view('categoryShow', compact('category'));
    }

    /**
     * Hiển thị form cập nhật
     */
    public function edit($slug)
    {
        //Tìm danh mục cần sửa
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục không tồn tại');
        }

        //Chuyển đến trang cập nhật
        return view('categoryUpdate', ['category' => $category]);
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $slug)
    {
        $validator = $request->validate([
            'category_name' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters], 
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'category_name.required' => 'Vui lòng nhập tên danh mục',
            'category_name.max' => 'Tên danh mục không được quá 50 ký tự',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $category = Category::where('slug', $slug)->first();    

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/category'), $filename);

            // Delete old image if exists
            if ($category->image && file_exists(public_path('img/category/' . $category->image))) {
                unlink(public_path('img/category/' . $category->image));
            }

            // Update with new image
            $category->image = $filename;
        }

        // Update other fields
        $category->category_name = $request->input('category_name');
        // Tạo slug từ tên danh mục mới
        $category->slug = $this->slugService->slugify($request->input('category_name')); // Sử dụng hàm slugify để tạo slug
        $category->updated_at = now();
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Xóa 1 danh mục khỏi database
     */
    public function destroy($slug)
    {
        // Kiểm tra xem danh mục có tồn tại không
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục không tồn tại.');
        }
        // Delete image if exists
        if ($category->image && file_exists(public_path('img/category/' . $category->image))) {
            unlink(public_path('img/category/' . $category->image));
        }
        // Thực hiện xóa nhà sản xuất
        try {
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Danh mục đã được xóa thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi khi xóa không thành công
            return redirect()->route('category.index')->with('error', 'Xóa danh mục không thành công.');
        }
    }
    // Sắp xếp theo tên, ngày cập nhật
    public function sortCategories(Request $request)
    {
        $query = Category::query();

        // Sắp xếp theo yêu cầu
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('category_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('category_name', 'desc');
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

        $categories = $query->paginate(5); // Phân trang

        return view('categoryAdmin', compact('categories'));
    }
    // Tìm kiếm danh mục theo tên
    public function searchCategories(Request $request)
    {
        $query = $request->input('query');

        // Tìm kiếm thông thường bằng tên danh mục
        $categories = Category::where('category_name', 'like', '%' . $query . '%')->paginate(5);

        return view('categoryAdmin', compact('categories'));
    }
    
    
}
