<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\SlugService;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo người dùng quản trị viên
        $adminFullname = 'Adminstrator';

        // Chèn người dùng quản trị viên vào cơ sở dữ liệu và lưu lại
        $adminUser = User::create([
            'fullname' => $adminFullname,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin12345'),
            'address' => '123 Street',
            'phone' => '0123456789',
            'image' => null,
            'role' => 'admin',
            'permission' => 'admin',
            'is_online' => false,
        ]);
        
        // Tạo slug cho quản trị viên với ID mã hóa
        $adminUser->slug = $this->generateUniqueSlug($adminFullname, $adminUser->user_id);
        $adminUser->save();

        // Tạo người dùng editor
        for ($i = 1; $i <= 5; $i++) {
            $letter = chr(96 + $i);
            $fullname = 'Editor ' . ucfirst($letter);

            // Chèn người dùng vào cơ sở dữ liệu và lưu lại
            $user = User::create([
                'fullname' => $fullname,
                'email' => 'editor' . $i . '@gmail.com',
                'password' => bcrypt('editor123' . $i),
                'address' => '123 Street',
                'phone' => '0123456789',
                'image' => null,
                'role' => 'editor',
                'permission' => 'editor',
                'is_online' => false,
            ]);

            // Tạo slug cho người dùng thường với ID mã hóa
            $user->slug = $this->generateUniqueSlug($fullname, $user->user_id);
            $user->save();
        }

        // Tạo người dùng thường
        for ($i = 1; $i < 20; $i++) {
            $letter = chr(96 + $i);
            $fullname = 'Nguoi dung ' . ucfirst($letter);

            // Chèn người dùng thường vào cơ sở dữ liệu và lưu lại
            $user = User::create([
                'fullname' => $fullname,
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('user123' . $i),
                'address' => '123 Street',
                'phone' => '0123456789',
                'image' => null,
                'role' => 'user',
                'permission' => 'viewer',
                'is_online' => false,
            ]);

            // Tạo slug cho người dùng thường với ID mã hóa
            $user->slug = $this->generateUniqueSlug($fullname, $user->user_id);
            $user->save();
        }
    }

    // Hàm tạo slug không trùng lặp
    protected function generateUniqueSlug($fullname, $userId): string
    {
        // Tạo slug từ fullname
        $slug = SlugService::slugify($fullname);

        // Mã hóa ID người dùng
        $encodedId = base64_encode($userId); // Mã hóa ID người dùng

        // Tạo slug duy nhất bằng cách thêm ID đã mã hóa vào cuối slug
        $uniqueSlug = $slug . '_' . $encodedId;

        return $uniqueSlug; // Trả về slug duy nhất
    }
}