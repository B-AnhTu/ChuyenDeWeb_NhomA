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

        else {
            // Nếu không có sắp xếp, sắp xếp theo ngày tạo mới
            $query = $query->orderBy('created_at', 'desc');
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
        if (!$manufacturer) {
            Session::flash('error', 'Nhà sản xuất không tồn tại');
            return redirect()->route('manufacturer.index')->withInput();
        }
        return view('manufacturerShow', compact('manufacturer'));
    }

    /**
     * Hiển thị trang cập nhật
     */
    public function edit($slug)
    {
        $manufacturer = $this->manufacturerService->getManufacturerBySlug($slug);
        if (!$manufacturer) {
            Session::flash('error', 'Nhà sản xuất không tồn tại');
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
                Session::flash('error', 'Nhà sản xuất không tồn tại');
                return redirect()->route('manufacturerAdmin.index')->withInput();
            }
    
            // Lưu dữ liệu đã validated
            $validatedData = $request->validated();
    
            // Gọi service để cập nhật manufacturer
            $this->manufacturerService->updateManufacturer($manufacturer, $validatedData);
            
            // Thông báo thành công
            Session::flash('success', 'Cập nhật nhà sản xuất thành công');
            return redirect()->route('manufacturer.index')->with('success', 'Cập nhật nhà sản xuất thành công');
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
        // Tìm manufacturer theo slug
        $manufacturer = $this->manufacturerService->getManufacturerBySlug($slug);
        // Kiểm tra nếu manufacturer không tồn tại
        if (!$manufacturer) {
            Session::flash('error', 'Nhà sản xuất không tồn tại');
            return redirect()->route('manufacturer.index')->withInput();
        }
        try {
            // Gọi service để xóa manufacturer
            $this->manufacturerService->deleteManufacturer($slug);
            
            // Thông báo thành công
            return redirect()->route('manufacturer.index')->with('success', 'Xóa nhà sản xuất thành công.');
        } catch (\Exception $e) {
            // Thông báo lỗi
            Session::flash('error', $e->getMessage());
            return redirect()->route('manufacturer.index')->withInput(); 
        }
    }
    
    
}
