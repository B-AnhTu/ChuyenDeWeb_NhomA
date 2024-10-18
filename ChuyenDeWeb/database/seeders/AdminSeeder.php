<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'Administrator',
            'phone' => '0987654321',
            'image' => null,
            'permission' => 'Full Access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
