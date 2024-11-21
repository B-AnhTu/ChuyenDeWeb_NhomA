<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
            'category_name' => 'Cellphone',
            'image' => 'cellphone.jpg',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Tablet',
            'image' => 'tablet.png',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Tivi',
            'image' => 'tv.jpg',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Headphones',
            'image' => 'headphones.png',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Speaker',
            'image' => 'speaker.png',
            'created_at' => now(),
            ],
        ];
        foreach ($categories as $category) {
            // Tạo danh mục
            $newCategory = Category::create($category);
            // Tạo slug từ category_name và ID của danh mục
            $newCategory->slug = Category::generateUniqueSlug($newCategory->category_name, $newCategory->category_id);
            $newCategory->save();
        }
    }
    
}
