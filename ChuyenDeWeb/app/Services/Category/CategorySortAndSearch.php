<?php

namespace App\Services\Category;

use App\Models\Category;

class CategorySortAndSearch
{
    public function searchCategories($searchTerm)
    {
        // Tìm kiếm
        return Category::search($searchTerm);

    }
    // Phương thức sắp xếp danh mục
    public function sortCategories($query, $sortBy)
    {
        return Category::sortCategories($query, $sortBy);
    }
}