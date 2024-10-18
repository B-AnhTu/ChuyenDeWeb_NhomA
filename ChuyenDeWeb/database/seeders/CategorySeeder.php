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
            'category_name' => 'Điện thoại',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category')->insert([
            'category_name' => 'Máy tính',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category')->insert([
            'category_name' => 'Máy tính bàn',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category')->insert([
            'category_name' => 'Tai nghe',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category')->insert([
            'category_name' => 'Dây sạc',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
