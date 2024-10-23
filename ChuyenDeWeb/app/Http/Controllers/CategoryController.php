<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\TextOnly;
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
            'category_name' => ['required', 'string', 'max:50', new TextOnly, new SingleSpaceOnly],
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'category_name.required' => 'Vui lòng nhập tên danh mục',
            'category_name.max' => 'Tên danh mục không được quá 50 ký tự',
            'image.required' => 'Vui lòng chọn hình ảnh để tải lên',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $data = $request->all();

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
        ]);
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //Tìm id của danh mục cần xem
        $category_id = $request->get('category_id');
        $category = Category::find($category_id);
        return view('categoryShow', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $category_id)
    {
        //Tìm id của danh mục cần sửa
        // $manufacturer_id = $request->get('manufacturer_id');
        $category = Category::findOrFail($category_id);

        //Chuyển đến trang cập nhật
        return view('categoryUpdate', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'category_name' => ['required', 'string', 'max:50', new TextOnly, new SingleSpaceOnly], 
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'category_name.required' => 'Vui lòng nhập tên danh mục',
            'category_name.max' => 'Tên danh mục không được quá 50 ký tự',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $category = Category::find($id);

        if(!$category){
            return redirect()->route('category.index')->with('error', 'Danh mục không tồn tại.');
        }

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
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $category_id)
    {
        // Kiểm tra xem danh mục có tồn tại không
        $category = Category::findOrFail($category_id);
        if (!$category) {
            return redirect()->route('category.index')->with('error', 'Danh mục không tồn tại.');
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
}
