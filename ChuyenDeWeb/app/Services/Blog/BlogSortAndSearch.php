<?php
namespace App\Services\Blog;

use App\Models\Blog;

class BlogSortAndSearch
{
    /**
     * Perform sorting and searching on blogs.
     */
    public function handle($filters)
    {
        $query = Blog::query();

        // Tìm kiếm
        if (!empty($filters['searchTerm'])) {
            $query->searchFullText($filters['searchTerm']);
        }

        // Sắp xếp
        if (!empty($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'name_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'description_asc':
                    $query->orderBy('short_description', 'asc');
                    break;
                case 'description_desc':
                    $query->orderBy('short_description', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        }

        // Phân trang
        return $query->paginate(5); // 10 bài viết mỗi trang
    }
}
