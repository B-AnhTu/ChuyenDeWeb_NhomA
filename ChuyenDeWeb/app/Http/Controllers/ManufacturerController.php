<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Rules\SingleSpaceOnly;

class ManufacturerController extends Controller
{
    public function index()
    {
        $manufacturers = Manufacturer::paginate(5);
        return view('manufacturerAdmin', ['manufacturers' => $manufacturers]);
    }

    /**
     * Hiển thị trang thêm nhà sản xuất
     */
    public function create()
    {
        // Chuyển đến trang thêm nhà sản xuất
        return view('manufacturerCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'manufacturer_name' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters],
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'manufacturer_name.required' => 'Vui lòng nhập tên nhà sản xuất',
            'manufacturer_name.max' => 'Tên nhà sản xuất không được quá 50 ký tự',
            'image.required' => 'Vui lòng chọn hình ảnh để tải lên',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $data = $request->all();

        // Tạo slug từ tên nhà sản xuất
        $data['slug'] = $this->slugify($data['manufacturer_name']); // Sử dụng hàm slugify để tạo slug

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/manufacturer'), $filename);

            // Cập nhật ảnh mới trong database
            $data['image'] = $filename;
        }

        $manufacturer = Manufacturer::create([
            'manufacturer_name' => $data['manufacturer_name'],
            'image' => $data['image'],
            'slug' => $data['slug'],
        ]);
        $manufacturer->save();

        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer created successfully');
    }
    
    /**
     * Hiển thị trang chi tiết nhà sản xuất
     */
    public function show($slug)
    {
        $manufacturer = Manufacturer::where('slug', $slug)->first();
        if (!$manufacturer) {
            return redirect()->route('manufacturer.index')->with('error', 'Nhà sản xuất không tồn tại');
        }
        return view('manufacturerShow', compact('manufacturer'));
    }

    /**
     * Hiển thị trang cập nhật
     */
    public function edit($slug)
    {
        //Tìm id của nhà sản xuất cần sửa
        // $slug = $request->get('s$slug');
        $manufacturer = Manufacturer::where('slug', $slug)->first();
        if (!$manufacturer) {
            return redirect()->route('manufacturer.index')->with('error', 'Nhà sản xuất không tồn tại');
        }

        //Chuyển đến trang cập nhật
        return view('manufacturerUpdate', ['manufacturer' => $manufacturer]);
    }

    /**
     * Cập nhật nhà sản xuất
     */
    public function update(Request $request, $slug)
    {
        $validator = $request->validate([
            'manufacturer_name' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters],
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'manufacturer_name.required' => 'Vui lòng nhập tên nhà sản xuất',
            'manufacturer_name.max' => 'Tên nhà sản xuất không được quá 50 ký tự',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $manufacturer = Manufacturer::where('slug', $slug)->first();

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/manufacturer'), $filename);

            // Delete old image if exists
            if ($manufacturer->image && file_exists(public_path('img/manufacturer/' . $manufacturer->image))) {
                unlink(public_path('img/manufacturer/' . $manufacturer->image));
            }

            // Update with new image
            $manufacturer->image = $filename;
        }

        // Update other fields
        $manufacturer->manufacturer_name = $request->input('manufacturer_name');
        // Tạo slug từ nhà sản xuất mới
        $manufacturer->slug = $this->slugify($request->input('manufacturer_name')); // Sử dụng hàm slugify để tạo slug
        $manufacturer->save();

        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $manufacturer = Manufacturer::where('slug', $slug)->first();
        // Kiểm tra xem nhà sản xuất có tồn tại không
        if (!$manufacturer) {
            return redirect()->route('manufacturer.index')->with('error', 'Nhà sản xuất không tồn tại.');
        }
        // Delete image if exists
        if ($manufacturer->image && file_exists(public_path('img/manufacturer/' . $manufacturer->image))) {
            unlink(public_path('img/manufacturer/' . $manufacturer->image));
        }
        // Thực hiện xóa nhà sản xuất
        try {
            $manufacturer->delete();
            return redirect()->route('manufacturer.index')->with('success', 'Nhà sản xuất đã được xóa thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi khi xóa không thành công
            return redirect()->route('manufacturer.index')->with('error', 'Xóa nhà sản xuất không thành công.');
        }
    }
    // Sắp xếp theo tên, ngày cập nhật
    public function sortManufacturers(Request $request)
    {
        $query = Manufacturer::query();

        // Sắp xếp theo yêu cầu
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('manufacturer_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('manufacturer_name', 'desc');
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

        $manufacturers = $query->paginate(5); // Phân trang

        return view('manufacturerAdmin', compact('manufacturers'));
    }
    // Tìm kiếm nhà sản xuất theo tên
    public function searchManufacturers(Request $request){
        $query = $request->input('query');

        $manufacturers = Manufacturer::where('manufacturer_name', 'like', '%' . $query . '%')->paginate(5);

        return view('manufacturerAdmin', compact('manufacturers'));
    }
    // Hàm để tạo slug
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
