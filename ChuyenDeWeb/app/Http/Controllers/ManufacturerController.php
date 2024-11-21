<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\Request;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Session; 
use App\Services\Manufacturer\ManufacturerService;
use App\Services\Manufacturer\ManufacturerSortAndSearch;
use App\Http\Requests\Manufacturer\StoreManufacturerRequest;
use App\Http\Requests\Manufacturer\UpdateManufacturerRequest;use App\Services\SlugService;

class ManufacturerController extends Controller
{
    protected $manufacturerService, $manufacturerSortAndSearch;

    public function __construct(ManufacturerService $manufacturerService, ManufacturerSortAndSearch $manufacturerSortAndSearch)
    {
        $this->manufacturerService = $manufacturerService;
        $this->manufacturerSortAndSearch = $manufacturerSortAndSearch;
    }
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm và lựa chọn sắp xếp từ request
        $searchTerm = $request->input('query');
        $sortBy = $request->input('sort_by');

        // Khởi tạo truy vấn
        $query = Manufacturer::query(); // Tạo một truy vấn mới

        // Nếu có tìm kiếm, thực hiện tìm kiếm
        if ($searchTerm) {
            $query= $this->manufacturerSortAndSearch->searchManufacturer($searchTerm);
        }

        // Nếu có sắp xếp, thực hiện sắp xếp
        if ($sortBy) {
            $query = $this->manufacturerSortAndSearch->sortManufacturer($query, $sortBy); // Gọi phương thức sắp xếp từ service
        }

        // Phân trang danh mục
        $manufacturers = $query->paginate(5);

        return view('manufacturerAdmin', [
            'manufacturers' => $manufacturers, // Phân trang
            'filters' => [
                'searchTerm' => $searchTerm,
                'sort_by' => $sortBy,
            ],
        ]);
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
    public function store(StoreManufacturerRequest $request)
    {
        $this->manufacturerService->createManufacturer($request->validated());
        return redirect()->route('manufacturer.index')->with('success', 'Manufacturer created successfully');
    }
    
    /**
     * Hiển thị trang chi tiết nhà sản xuất
     */
    public function show($slug)
    {
        $manufacturer = $this->manufacturerService->getManufacturerBySlug($slug);
        return view('manufacturerShow', compact('manufacturer'));
    }

    /**
     * Hiển thị trang cập nhật
     */
    public function edit($slug)
    {
        $manufacturer = $this->manufacturerService->getManufacturerBySlug($slug);
        if (!$manufacturer) {
            Session::flash('error', 'manu$manufacturer not found. It may have been deleted or modified by another user.');
            return redirect()->route('manufacturer.index')->withInput();
        }
        //Chuyển đến trang cập nhật
        return view('manufacturerUpdate', ['manufacturer' => $manufacturer]);
    }

    /**
     * Cập nhật nhà sản xuất
     */
    public function update(UpdateManufacturerRequest $request, $slug)
    {
        try {
            // Tìm manufacturer theo slug
            $manufacturer = $this->manufacturerService->getManufacturerBySlug($slug);
            // Kiểm tra nếu category không tồn tại
            if (!$manufacturer) {
                Session::flash('error', 'Manufacturer not found. It may have been deleted or modified by another user.');
                return redirect()->route('manufacturerAdmin.index')->withInput();
            }
    
            // Lưu dữ liệu đã validated
            $validatedData = $request->validated();
    
            // Gọi service để cập nhật manufacturer
            $this->manufacturerService->updateManufacturer($manufacturer, $validatedData);
            
            // Thông báo thành công
            Session::flash('success', 'Manufacturer updated successfully.');
            return redirect()->route('manufacturer.index')->with('success', 'Manufacturer updated successfully.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('category.edit', ['slug' => $slug])->withInput(); // Chuyển hướng về trang cập nhật
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        try {
            // Gọi service để xóa manufacturer
            $this->manufacturerService->deleteManufacturer($slug);
            
            // Thông báo thành công
            return redirect()->route('manufacturer.index')->with('success', 'Manufacturer deleted successfully.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('manufacturer.index')->withInput(); 
        }
    }
    // // Sắp xếp theo tên, ngày cập nhật
    // public function sortManufacturers(Request $request)
    // {
    //     $query = Manufacturer::query();

    //     // Sắp xếp theo yêu cầu
    //     if ($request->has('sort_by')) {
    //         switch ($request->sort_by) {
    //             case 'name_asc':
    //                 $query->orderBy('manufacturer_name', 'asc');
    //                 break;
    //             case 'name_desc':
    //                 $query->orderBy('manufacturer_name', 'desc');
    //                 break;
    //             case 'updated_at_asc':
    //                 $query->orderBy('updated_at', 'asc');
    //                 break;
    //             case 'updated_at_desc':
    //                 $query->orderBy('updated_at', 'desc');
    //                 break;
    //             default:
    //                 // Mặc định không sắp xếp
    //                 break;
    //         }
    //     }

    //     $manufacturers = $query->paginate(5); // Phân trang

    //     return view('manufacturerAdmin', compact('manufacturers'));
    // }
    // // Tìm kiếm nhà sản xuất theo tên
    // public function searchManufacturers(Request $request){
    //     $query = $request->input('query');

    //     $manufacturers = Manufacturer::where('manufacturer_name', 'like', '%' . $query . '%')->paginate(5);

    //     return view('manufacturerAdmin', compact('manufacturers'));
    // }
    
}
