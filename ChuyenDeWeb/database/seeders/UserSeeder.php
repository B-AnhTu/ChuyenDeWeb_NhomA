<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'fullname' => 'Adminstrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin12345'),
            'address' => '123 Street',
            'phone' => '0123456789',
            'image' => 'user-default.jpg',
            'role' => 'admin',
            'permission' => 'admin',
            'is_online' => false,
        ]);
        for ($i = 1; $i < 20; $i++) { 
            User::create([
                'fullname' => 'User'.$i,
                'email' => 'user'.$i.'@gmail.com',
                'password' => bcrypt('user123'.$i),
                'address' => '123 Street',
                'phone' => '0123456789',
                'image' => 'user-default.jpg',
                'role' => 'user',
                'permission' => 'viewer',
                'is_online' => false,
            ]);
        }
        
    }
}
