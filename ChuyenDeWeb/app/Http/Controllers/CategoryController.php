<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoryCreate');
    }

    /**
     * Store a newly created resource in storage.
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

        $data['slug'] = $this->slugify($data['category_name']); // Sử dụng hàm slugify để tạo slug

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
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
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
        $category->slug = $this->slugify($request->input('category_name')); // Sử dụng hàm slugify để tạo slug
        $category->update_at = now();
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
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
    // Sắp xếp theo tên, ngày cập nhật (quan ly user)
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
    // Tìm kiếm 
    public function searchCategories(Request $request)
    {
        $query = $request->input('query');

        // Tìm kiếm thông thường bằng tên danh mục
        $categories = Category::where('category_name', 'like', '%' . $query . '%')->paginate(5);

        return view('categoryAdmin', compact('categories'));
    }
    // Hàm để tạo slug từ title
    private function slugify($text)
    {
        // Chuyển đổi ký tự có dấu thành không dấu
        $text = $this->removeVietnameseAccent($text);
        
        // Thay thế nhiều khoảng trắng thành một khoảng trắng
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text); // Xóa khoảng trắng ở đầu và cuối
        $text = strtolower($text); // Chuyển thành chữ thường
        $text = str_replace(' ', '-', $text); // Thay dấu khoảng trắng bằng dấu gạch nối

        return $text;
    }

    // Hàm để loại bỏ dấu tiếng Việt
    private function removeVietnameseAccent($string)
    {
        $unicode = [
            'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'đ' => 'd',
            'À' => 'A', 'Á' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A',
            'Ă' => 'A', 'Ằ' => 'A', 'Ắ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A',
            'Â' => 'A', 'Ầ' => 'A', 'Ấ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
            'È' => 'E', 'É' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E',
            'Ê' => 'E', 'Ề' => 'E', 'Ế' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I',
            'Ò' => 'O', 'Ó' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O',
            'Ô' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O',
            'Ơ' => 'O', 'Ờ' => 'O', 'Ớ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U',
            'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
            'Ỳ' => 'Y', 'Ý' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y',
            'Đ' => 'D',
        ];
        return strtr($string, $unicode);
    }
}
