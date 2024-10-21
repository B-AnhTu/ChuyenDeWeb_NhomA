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
        ]);
    }
}
