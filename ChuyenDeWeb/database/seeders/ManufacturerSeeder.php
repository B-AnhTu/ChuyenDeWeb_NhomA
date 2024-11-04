<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('manufacturer')->insert([
            [
                'manufacturer_name' => 'Apple',
                'image' => 'logoapples.png',
                'slug' => 'apple',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Samsung',
                'image' => 'logosamsung.png',
                'slug' => 'samsung',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Xiaomi',
                'image' => 'logoxiaomi.png',
                'slug' => 'xiaomi',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Huawei',
                'image' => 'logohuawei.png',
                'slug' => 'huawei',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Google',
                'image' => 'logogoogel.png',
                'slug' => 'google',
                'created_at' => now(),
            ]
        ]);
    }
}
