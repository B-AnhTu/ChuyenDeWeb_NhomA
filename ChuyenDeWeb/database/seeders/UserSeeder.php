<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'fullname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin12345'),
            'address' => 'example',
            'phone' => '0921321311',
            'image' => null,
            'role' => 'admin',
            'permission' => 'admin',
            'is_online' => false,
        ]);
        for ($i = 1; $i < 20; $i++) { 
            DB::table('users')->insert([
                'fullname' => 'user'.$i,
                'email' => 'user'.$i.'@gmail.com',
                'password' => Hash::make('user12345'.$i),
                'address' => 'example',
                'phone' => '0987654321',
                'image' => null,
                'role' => 'user',
                'permission' => 'viewer',
                'is_online' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
    }
}
