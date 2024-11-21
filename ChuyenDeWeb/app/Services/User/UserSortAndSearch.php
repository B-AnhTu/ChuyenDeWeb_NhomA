<?php

namespace App\Services\User;

use App\Models\User;

class UserSortAndSearch
{
    public function searchUsers($searchTerm)
    {
        // Tìm kiếm
        return User::search($searchTerm);
    }
    public function sortUsers($query, $sortBy)
    {
        return User::sort($query, $sortBy);
    }
}