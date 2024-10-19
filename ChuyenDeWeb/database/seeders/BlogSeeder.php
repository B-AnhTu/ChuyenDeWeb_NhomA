<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blog')->insert([
            'image' => '',
            'title' => '10 Mẹo Bảo Quản Laptop Để Sử Dụng Lâu Dài',
            'short_description' => 'Bảo quản laptop đúng cách không chỉ giúp thiết bị hoạt động hiệu quả mà còn kéo dài tuổi thọ sử dụng. Hãy cùng khám phá 10 mẹo đơn giản để bảo trì laptop của bạn.',
            'content' => 'Laptop là một trong những thiết bị điện tử quan trọng nhất trong cuộc sống hàng ngày. Để đảm bảo rằng laptop của bạn luôn hoạt động tốt và bền bỉ, dưới đây là 10 mẹo bảo quản hiệu quả:
            1. Giữ Laptop Sạch Sẽ
                1.1. Thường xuyên lau chùi màn hình và bàn phím bằng khăn mềm. Tránh sử dụng hóa chất mạnh.
            2. Sử Dụng Balo Chống Sốc
                2.1. Khi di chuyển, hãy sử dụng balo hoặc túi chống sốc để bảo vệ laptop khỏi va đập.
            3. Tránh Nhiệt Độ Cao
                3.1. Đặt laptop ở nơi thoáng mát, tránh ánh nắng trực tiếp và nhiệt độ cao để không làm hỏng linh kiện bên trong.
            4. Cập Nhật Phần Mềm Thường Xuyên
                4.1. Đảm bảo hệ điều hành và các phần mềm luôn được cập nhật để bảo mật và hiệu suất tốt nhất.
            5. Sử Dụng Ổ Cứng Ngoài
                5.1. Lưu trữ dữ liệu quan trọng trên ổ cứng ngoài để giảm tải cho ổ cứng chính của laptop.
            6. Tắt Laptop Khi Không Sử Dụng
                6.1. Tắt hoàn toàn laptop khi không sử dụng lâu để tiết kiệm điện và bảo vệ pin.
            7. Kiểm Tra Pin Định Kỳ
                7.1. Theo dõi tình trạng pin và thay thế khi cần thiết để tránh tình trạng pin phồng hoặc hỏng.
            8. Sử Dụng Quạt Tản Nhiệt
                8.1. Nếu laptop của bạn hay bị nóng, hãy sử dụng quạt tản nhiệt để giữ cho nhiệt độ ổn định.
            9. Tránh Để Laptop Trên Giường
                9.1. Không đặt laptop trên giường hoặc bề mặt mềm khác, vì điều này có thể làm tắc lỗ thông gió.
            10. Thực Hiện Bảo Trì Định Kỳ
                10.1. Thực hiện các bước bảo trì như dọn dẹp ổ cứng và quét virus định kỳ để duy trì hiệu suất.
            ',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
