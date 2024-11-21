<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Manufacturer;



class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manufacturers = [
            [
                'manufacturer_name' => 'Apple',
                'image' => 'logoapples.png',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Samsung',
                'image' => 'logosamsung.png',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Xiaomi',
                'image' => 'logoxiaomi.png',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Huawei',
                'image' => 'logohuawei.png',
                'created_at' => now(),
            ],
            [
                'manufacturer_name' => 'Google',
                'image' => 'logogoogel.png',
                'created_at' => now(),
            ]
        ];
        foreach ($manufacturers as $manufacturer) {
            $newManufacturer = Manufacturer::create($manufacturer);
            // Tạo slug từ manufacturer_name
            $newManufacturer->slug = Manufacturer::generateUniqueSlug($newManufacturer->manufacturer_name, $newManufacturer->manufacturer_id);
            $newManufacturer->save();
        }
    }
}
