<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category')->insert([
            [
            'category_name' => 'Cellphone',
            'image' => 'cellphone.jpg',
            'slug' => 'cellphone',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Tablet',
            'image' => 'tablet.png',
            'slug' => 'tablet',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Tivi',
            'image' => 'tv.jpg',
            'slug' => 'tivi',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Headphones',
            'image' => 'headphones.png',
            'slug' => 'headphones',
            'created_at' => now(),
            ],
            [
            'category_name' => 'Speaker',
            'image' => 'speaker.png',
            'slug' => 'speaker',
            'created_at' => now(),
            ],
        ]);
    }
}
