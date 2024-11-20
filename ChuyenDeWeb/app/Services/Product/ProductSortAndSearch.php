<?php

namespace App\Services\Product;

use App\Models\Product;

class ProductSortAndSearch
{
    public function searchProducts($searchTerm)
    {
        // Tìm kiếm
        return Product::search($searchTerm);

    }
    // Phương thức sắp xếp danh mục
    public function sortProducts($query, $sortBy)
    {
        return Product::sort($query, $sortBy);
    }
}