<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\SlugService;


class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_name',
        'image',
        'slug'
    ];

    protected $table = 'manufacturer';
    protected $primaryKey = 'manufacturer_id'; // Specify the correct primary key

    // Hàm khởi tạo và cập nhật slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($manufacturer) {
            $manufacturer->slug = static::generateUniqueSlug($manufacturer->manufacturer_name, $manufacturer->manufacturer_id);
        });

        static::updating(function ($manufacturer) {
            $manufacturer->slug = static::generateUniqueSlug($manufacturer->manufacturer_name, $manufacturer->manufacturer_id);
        });
    }

    // Tạo slug không trùng lặp
    protected static function generateUniqueSlug($manufacturerName, $manufacturerId = null)
    {
        // Tạo slug từ manufacturer name
        $slug = SlugService::slugify($manufacturerName);

        // Mã hóa ID nhà sản xuất
        $encodedId = base64_encode($manufacturerId); // Mã hóa ID nhà sản xuất

        // Tạo slug duy nhất bằng cách thêm ID đã mã hóa vào cuối slug
        $uniqueSlug = $slug . '_' . $encodedId;

        return $uniqueSlug; // Trả về slug duy nhất
    }
    // Phương thức giải mã slug để lấy ID nhà sản xuất
    public static function decodeSlug($slug)
    {
        // Tách slug thành phần
        $parts = explode('_', $slug);
        if (count($parts) < 2) {
            return null; // Nếu không có ID, trả về null
        }

        // Lấy phần cuối cùng (ID đã mã hóa)
        $encodedId = end($parts); // Lấy phần cuối cùng
        $decodedId = base64_decode($encodedId); // Giải mã base64

        return $decodedId; // Trả về ID nhà sản xuất
    }

    /** 
     * Phương thức lấy tất cả Manufacturer (có phân trang) 
     * */
    public static function getAllManufacturer()
    {
        return self::paginate(5);
    }
    // Lấy manufacturer theo id
    public static function getManufacturerById($id){
        return self::find($id);
    }

    /**
     * Tạo manufacturer mới
     */
    public static function createManufacturer(array $data)
    {
        return self::create($data);
    }

    /**
     * Cập nhật Manufacturer
     */
    public function updateWithConflictCheck(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Lưu giá trị updated_at hiện tại trước khi cập nhật
            $currentUpdatedAt = $this->updated_at;

            // Kiểm tra xung đột trước khi thực hiện cập nhật
            if ($currentUpdatedAt != $this->updated_at) {
                throw new \Exception('Conflict detected. The manufacturer has been updated by another user.');
            }

            // Kiểm tra slug mới từ manufacturer_name
            $newSlug = $data['slug'] ?? $this->slug; // Lấy slug mới từ dữ liệu
            $slugChanged = $newSlug !== $this->slug; // Kiểm tra slug đã thay đổi

            // Cập nhật thông tin cho manufacturer
            if (isset($data['image'])) {
                $file = $data['image'];
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/manufacturer'), $filename);

                // Xóa ảnh cũ nếu có
                if ($this->image && file_exists(public_path('img/manufacturer/' . $this->image))) {
                    unlink(public_path('img/manufacturer/' . $this->image));
                }

                $data['image'] = $filename;
            }

            $data['updated_at'] = now();

            // 3. Cập nhật manufacturer
            $this->update($data);

            return $this; // Trả về manufacturer đã cập nhật
        });
    }

    /**
     * Xóa manufacturer
     */
    public static function deletemanufacturerBySlug($slug)
    {
        $manufacturer_id = self::decodeSlug($slug);
        $manufacturer = self::getManufacturerById($manufacturer_id);
        if ($manufacturer) {
            // Kiểm tra và xóa hình ảnh nếu có
            if ($manufacturer->image && file_exists(public_path('img/manufacturer/' . $manufacturer->image))) {
                unlink(public_path('img/manufacturer/' . $manufacturer->image));
            }
            $manufacturer->delete();
            return true;
        }
    }
    /**
     * Tìm kiếm
     */
    public static function search($searchTerm)
    {
        if ($searchTerm) {
            return self::where('manufacturer_name', 'like', '%' . $searchTerm . '%');
        }
        return self::all();
    }
    /**
     * Sắp xếp
     */
    public static function sortManufacturer($query, $sortBy)
    {
        // Sắp xếp theo yêu cầu
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('manufacturer_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('manufacturer_name', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                // Mặc định 
                $query->orderBy('created_at', 'desc');
                break;
        }
        return $query;
    }
}
