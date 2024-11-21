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

        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        // Tạo người dùng mới
        $manufacturer = Manufacturer::createManufacturer($validatedData);

        // Tạo slug cho người dùng sau khi đã tạo
        $manufacturer->slug = Manufacturer::generateUniqueSlug($manufacturer->manufacturer_name, $manufacturer->manufacturer_id);
        $manufacturer->save();

        return $manufacturer; // Trả về người dùng đã tạo
    }
    /**
     * Lấy nhà sản xuất theo slug
     */
    public function getManufacturerBySlug($slug){
        $manufacturerId = Manufacturer::decodeSlug($slug); // Giải mã slug để lấy ID sản phẩm
        return Manufacturer::find($manufacturerId);
    }
    /**
     * Sửa nhà sản xuất ( có kiểm tra lỗi bảo mật Optimistic Locking)
     */
    public function updateManufacturer($manufacturer, $validatedData)
    {
        $validatedData['updated_at'] = now();

        // Cập nhật thông tin người dùng
        $manufacturer->updateWithConflictCheck($validatedData);

        // Nếu fullname đã thay đổi, tạo lại slug
        if ($manufacturer->manufacturer_name !== $validatedData['manufacturer_name']) {
            $manufacturer->slug = Manufacturer::generateUniqueSlug($validatedData['manufacturer_name'], $validatedData['manufacturer_id']);
        }

        $manufacturer->save();

        return $manufacturer; // Trả về người dùng đã cập nhật
    }
    /**
     * Xóa nhà sản xuất
     */
    public function deleteManufacturer($slug)
    {
        return Manufacturer::deletemanufacturerBySlug($slug);
    }
}
