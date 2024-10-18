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
            'manufacturer_name' => 'Huawei',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('manufacturer')->insert([
            'manufacturer_name' => 'Samsung',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('manufacturer')->insert([
            'manufacturer_name' => 'Apple',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('manufacturer')->insert([
            'manufacturer_name' => 'Oppo',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
