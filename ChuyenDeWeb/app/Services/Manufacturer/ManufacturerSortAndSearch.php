<?php

namespace App\Services\Manufacturer;

use App\Models\Manufacturer;

class ManufacturerSortAndSearch
{
    public function searchManufacturer($searchTerm)
    {
        // Tìm kiếm
        return Manufacturer::search($searchTerm);

    }
    // Phương thức sắp xếp danh mục
    public function sortManufacturer($query, $sortBy)
    {
        return Manufacturer::sortManufacturer($query, $sortBy);
    }
}