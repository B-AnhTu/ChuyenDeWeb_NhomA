<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Rules\SingleSpaceOnly;

class ManufacturerController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manufacturers = Manufacturer::paginate(5);
        return view('manufacturerAdmin', ['manufacturers' => $manufacturers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Chuyển đến trang thêm nhà sản xuất
        return view('manufacturerCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'manufacturer_name' => ['required', 'string', 'max:50', new SingleSpaceOnly],
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'manufacturer_name.required' => 'Vui lòng nhập tên nhà sản xuất',
            'manufacturer_name.max' => 'Tên nhà sản xuất không được quá 50 ký tự',
            'image.required' => 'Vui lòng chọn hình ảnh để tải lên',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $data = $request->all();

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
        ]);
        $manufacturer->save();

        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer created successfully');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $manufacturer_id)
    {
        $manufacturer = Manufacturer::findOrFail($manufacturer_id);
        if (!$manufacturer) {
            return redirect()->route('manufacturer.index')->with('error', 'Nhà sản xuất không tồn tại');
        }
        return view('manufacturerShow', compact('manufacturer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $manufacturer_id)
    {
        //Tìm id của nhà sản xuất cần sửa
        // $manufacturer_id = $request->get('manufacturer_id');
        $manufacturer = Manufacturer::findOrFail($manufacturer_id);

        //Chuyển đến trang cập nhật
        return view('manufacturerUpdate', ['manufacturer' => $manufacturer]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'manufacturer_name' => ['required', 'string', 'max:50', new SingleSpaceOnly],
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120', 
        ], [
            'manufacturer_name.required' => 'Vui lòng nhập tên nhà sản xuất',
            'manufacturer_name.max' => 'Tên nhà sản xuất không được quá 50 ký tự',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ]);

        $manufacturer = Manufacturer::find($id);

        if(!$manufacturer){
            return redirect()->route('manufacturer.index')->with('error', 'Nhà sản xuất không tồn tại.');
        }

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
        $manufacturer->save();

        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $manufacturer_id)
    {
        // $manufacturer_id = $request->get('manufacturer_id');

        // Kiểm tra xem nhà sản xuất có tồn tại không
        $manufacturer = Manufacturer::findOrFail($manufacturer_id);
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
    // Sắp xếp theo tên, ngày cập nhật (quan ly user)
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
}
