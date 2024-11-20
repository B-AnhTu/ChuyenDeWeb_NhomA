<?php
namespace App\Services\Manufacturer;

use App\Models\Manufacturer;
use Illuminate\Support\Facades\Auth;
use App\Services\SlugService;
use Illuminate\Support\Facades\DB;

class ManufacturerService
{
    protected $slugService; // Khai báo thuộc tính

    // Constructor để nhận SlugService thông qua dependency injection
    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService; // Khởi tạo thuộc tính slugService
    }
    // Lấy danh sách nhà sản xuất có phân trang
    public function getAllManufacturer()
    {
        return Manufacturer::getAllManufacturer();
    }
    /**
     * Thêm nhà sản xuất
     */
    public function createManufacturer($validatedData)
    {
        if (isset($validatedData['image'])) {
            $file = $validatedData['image'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/manufacturer'), $filename);
            $validatedData['image'] = $filename;
        }

        $validatedData['slug'] = $this->slugService->slugify($validatedData['manufacturer_name']);
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        return Manufacturer::createManufacturer($validatedData);
    }
    /**
     * Lấy nhà sản xuất theo slug
     */
    public function getManufacturerBySlug($manufacturerSlug){
        return Manufacturer::getManufacturerBySlug($manufacturerSlug);
    }
    /**
     * Sửa nhà sản xuất ( có kiểm tra lỗi bảo mật Optimistic Locking)
     */
    public function updateManufacturer($manufacturer, $validatedData)
    {
        // Làm mới slug dựa trên manufacturer_name
        $validatedData['slug'] = $this->slugService->slugify($validatedData['manufacturer_name']);

        return $manufacturer->updateWithConflictCheck($validatedData);
    }
    /**
     * Xóa nhà sản xuất
     */
    public function deleteManufacturer($slug)
    {
        // Tìm Manufacturer theo slug
        $manufacturer = Manufacturer::getManufacturerBySlug($slug);

        // Kiểm tra xem Manufacturer có tồn tại không
        if (!$manufacturer) {
            throw new \Exception('Không tìm thấy nhà sản xuất. Có thể nhà sản xuất đã bị chỉnh sửa hoặc xóa bởi người dùng khác.');
        }

        // Thực hiện xóa Manufacturer
        try {
            Manufacturer::deleteManufacturerBySlug($manufacturer->slug);

        } catch (\Exception $e) {
            // Xử lý lỗi trong trường hợp xóa không thành công
            throw new \Exception('Xảy ra lỗi trong khi cố gắng xóa nhà sản xuất: ' . $e->getMessage());
        }

        // Trả về true nếu xóa thành công
        return true;
    }
}
