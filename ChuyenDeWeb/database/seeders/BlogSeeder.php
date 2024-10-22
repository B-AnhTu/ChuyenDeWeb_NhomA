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
            'image' => 'blog-01.jpg',
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

        DB::table('blog')->insert([
            'image' => 'blog-02.jpg',
            'title' => '5 Lợi Ích Của Việc Sử Dụng Laptop Đúng Cách',
            'short_description' => 'Sử dụng laptop đúng cách không chỉ giúp tăng hiệu suất mà còn bảo vệ sức khỏe người dùng. Khám phá 5 lợi ích quan trọng.',
            'content' => 'Việc sử dụng laptop đúng cách mang lại nhiều lợi ích cho người dùng. Dưới đây là 5 lợi ích nổi bật:
            1. Tăng Tuổi Thọ Thiết Bị
                1.1. Sử dụng laptop đúng cách giúp kéo dài tuổi thọ của thiết bị, giảm thiểu chi phí sửa chữa.
            2. Cải Thiện Hiệu Suất
                2.1. Khi laptop được bảo quản và sử dụng đúng, hiệu suất làm việc sẽ được cải thiện rõ rệt.
            3. Bảo Vệ Sức Khỏe
                3.1. Sử dụng laptop đúng tư thế và thời gian hợp lý giúp bảo vệ sức khỏe cột sống và mắt.
            4. Tiết Kiệm Năng Lượng
                4.1. Tắt máy khi không sử dụng giúp tiết kiệm điện năng và bảo vệ môi trường.
            5. Dễ Dàng Bảo Trì
                5.1. Laptop được sử dụng đúng cách sẽ dễ dàng hơn trong việc bảo trì và nâng cấp phần cứng.',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-03.jpg',
            'title' => 'Cách Giải Quyết Vấn Đề Nhiệt Độ Cao Của Laptop',
            'short_description' => 'Nhiệt độ cao có thể gây hại cho laptop. Tìm hiểu cách giải quyết vấn đề này hiệu quả.',
            'content' => 'Nhiệt độ cao có thể ảnh hưởng nghiêm trọng đến hiệu suất và tuổi thọ của laptop. Dưới đây là một số cách giải quyết:
            1. Vệ Sinh Quạt Tản Nhiệt
                1.1. Đảm bảo quạt tản nhiệt sạch sẽ để không bị cản trở lưu thông không khí.
            2. Sử Dụng Đế Tản Nhiệt
                2.1. Sử dụng đế tản nhiệt giúp giảm nhiệt độ và cải thiện hiệu suất làm việc.
            3. Tránh Sử Dụng Trên Bề Mặt Mềm
                3.1. Không đặt laptop trên giường hoặc ghế sofa, vì điều này có thể làm tắc lỗ thông gió.
            4. Kiểm Tra Phần Mềm
                4.1. Đảm bảo không có phần mềm nào đang chiếm dụng quá nhiều tài nguyên hệ thống, gây nóng máy.
            5. Đặt Laptop Ở Nơi Thoáng Mát
                5.1. Đặt laptop ở nơi có không khí lưu thông tốt và tránh ánh nắng trực tiếp.',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-04.jpg',
            'title' => '10 Mẹo Bảo Quản Điện Thoại Để Sử Dụng Bền Lâu',
            'short_description' => 'Bảo quản điện thoại đúng cách giúp thiết bị hoạt động hiệu quả và kéo dài tuổi thọ.',
            'content' => 'Để đảm bảo điện thoại của bạn luôn hoạt động tốt, dưới đây là 10 mẹo bảo quản hiệu quả:
            1. Sử Dụng Ốp Lưng
                1.1. Sử dụng ốp lưng để bảo vệ điện thoại khỏi va đập và trầy xước.
            2. Tránh Nhiệt Độ Cao
                2.1. Không để điện thoại ở nơi có nhiệt độ cao, như trên bảng điều khiển xe hơi.
            3. Lau Chùi Thường Xuyên
                3.1. Thường xuyên lau màn hình bằng khăn mềm để giữ cho màn hình sạch sẽ.
            4. Tắt Ứng Dụng Không Cần Thiết
                4.1. Tắt các ứng dụng không cần thiết để tiết kiệm pin và giảm tải cho hệ thống.
            5. Sử Dụng Sạc Chính Hãng
                5.1. Luôn sử dụng sạc chính hãng để đảm bảo an toàn cho pin.',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-05.jpg',
            'title' => 'Cách Bảo Quản Máy Tính Bảng Để Sử Dụng Lâu Dài',
            'short_description' => 'Bảo quản máy tính bảng đúng cách giúp tăng cường hiệu suất và kéo dài tuổi thọ.',
            'content' => 'Máy tính bảng là thiết bị hữu ích trong cuộc sống hàng ngày. Dưới đây là một số mẹo bảo quản:
            1. Sử Dụng Bao Bì Chống Sốc
                1.1. Khi di chuyển, hãy sử dụng bao bì chống sốc để bảo vệ máy tính bảng khỏi va đập.
            2. Đặt Ở Nơi Thoáng Mát
                2.1. Tránh để máy tính bảng ở nơi có nhiệt độ cao hoặc ẩm ướt.
            3. Cập Nhật Phần Mềm
                3.1. Đảm bảo cập nhật phần mềm thường xuyên để bảo mật và hiệu suất tối ưu.
            4. Sử Dụng Màn Hình Bảo Vệ
                4.1. Sử dụng màn hình bảo vệ để tránh trầy xước màn hình.
            5. Tắt Khi Không Sử Dụng
                5.1. Tắt máy tính bảng khi không sử dụng lâu để tiết kiệm pin.',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-06.jpg',
            'title' => 'Cách Bảo Quản Tai Nghe Để Sử Dụng Bền Lâu',
            'short_description' => 'Bảo quản tai nghe đúng cách giúp tăng cường độ bền và chất lượng âm thanh.',
            'content' => 'Tai nghe là thiết bị không thể thiếu trong cuộc sống hiện đại. Dưới đây là một số mẹo bảo quản:
            1. Gập Dây Tai Nghe Đúng Cách
                1.1. Gập dây tai nghe theo cách không làm hỏng dây dẫn để tránh đứt ngầm.
            2. Sử Dụng Hộp Đựng
                2.1. Luôn để tai nghe trong hộp đựng khi không sử dụng để bảo vệ khỏi bụi bẩn.
            3. Tránh Để Ở Nơi Ẩm Ướt
                3.1. Không để tai nghe ở nơi ẩm ướt, vì điều này có thể làm hỏng linh kiện điện tử.
            4. Vệ Sinh Thường Xuyên
                4.1. Vệ sinh tai nghe định kỳ để loại bỏ bụi bẩn và vi khuẩn.
            5. Sử Dụng Với Âm Lượng Thích Hợp
                5.1. Tránh nghe nhạc với âm lượng quá lớn để bảo vệ tai và kéo dài tuổi thọ của tai nghe.',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
