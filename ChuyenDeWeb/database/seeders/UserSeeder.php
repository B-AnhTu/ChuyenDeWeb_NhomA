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
        for ($i = 1; $i < 20; $i++) { 
            DB::table('users')->insert([
                'fullname' => 'user'.$i,
                'email' => 'user'.$i.'@gmail.com',
                'password' => Hash::make('user12345'.$i),
                'address' => 'example',
                'phone' => 0132716732,
                'image' => null,
                'is_online' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
    }
}
