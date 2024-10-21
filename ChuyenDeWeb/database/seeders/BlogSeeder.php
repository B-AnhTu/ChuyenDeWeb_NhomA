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
            1. Giữ Laptop Sạch Sẽ </br>
                1.1. Thường xuyên lau chùi màn hình và bàn phím bằng khăn mềm. Tránh sử dụng hóa chất mạnh.</br>
            2. Sử Dụng Balo Chống Sốc</br>
                2.1. Khi di chuyển, hãy sử dụng balo hoặc túi chống sốc để bảo vệ laptop khỏi va đập.</br>
            3. Tránh Nhiệt Độ Cao</br>
                3.1. Đặt laptop ở nơi thoáng mát, tránh ánh nắng trực tiếp và nhiệt độ cao để không làm hỏng linh kiện bên trong.</br>
            4. Cập Nhật Phần Mềm Thường Xuyên</br>
                4.1. Đảm bảo hệ điều hành và các phần mềm luôn được cập nhật để bảo mật và hiệu suất tốt nhất.</br>
            5. Sử Dụng Ổ Cứng Ngoài</br>
                5.1. Lưu trữ dữ liệu quan trọng trên ổ cứng ngoài để giảm tải cho ổ cứng chính của laptop.</br>
            6. Tắt Laptop Khi Không Sử Dụng</br>
                6.1. Tắt hoàn toàn laptop khi không sử dụng lâu để tiết kiệm điện và bảo vệ pin.</br>
            7. Kiểm Tra Pin Định Kỳ</br>
                7.1. Theo dõi tình trạng pin và thay thế khi cần thiết để tránh tình trạng pin phồng hoặc hỏng.</br>
            8. Sử Dụng Quạt Tản Nhiệt</br>
                8.1. Nếu laptop của bạn hay bị nóng, hãy sử dụng quạt tản nhiệt để giữ cho nhiệt độ ổn định.</br>
            9. Tránh Để Laptop Trên Giường</br>
                9.1. Không đặt laptop trên giường hoặc bề mặt mềm khác, vì điều này có thể làm tắc lỗ thông gió.</br>
            10. Thực Hiện Bảo Trì Định Kỳ</br>
                10.1. Thực hiện các bước bảo trì như dọn dẹp ổ cứng và quét virus định kỳ để duy trì hiệu suất.</br>
            ',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('blog')->insert([
            'image' => 'blog-02.jpg',
            'title' => '5 Lợi Ích Của Việc Sử Dụng Laptop Đúng Cách',
            'short_description' => 'Sử dụng laptop đúng cách không chỉ giúp tăng hiệu suất mà còn bảo vệ sức khỏe người dùng. Khám phá 5 lợi ích quan trọng.',
            'content' => 'Việc sử dụng laptop đúng cách mang lại nhiều lợi ích cho người dùng.</br> Dưới đây là 5 lợi ích nổi bật:</br>
            1. Tăng Tuổi Thọ Thiết Bị</br>
                1.1. Sử dụng laptop đúng cách giúp kéo dài tuổi thọ của thiết bị, giảm thiểu chi phí sửa chữa.</br>
            2. Cải Thiện Hiệu Suất</br>
                2.1. Khi laptop được bảo quản và sử dụng đúng, hiệu suất làm việc sẽ được cải thiện rõ rệt.</br>
            3. Bảo Vệ Sức Khỏe</br>
                3.1. Sử dụng laptop đúng tư thế và thời gian hợp lý giúp bảo vệ sức khỏe cột sống và mắt.</br>
            4. Tiết Kiệm Năng Lượng</br>
                4.1. Tắt máy khi không sử dụng giúp tiết kiệm điện năng và bảo vệ môi trường.</br>
            5. Dễ Dàng Bảo Trì</br>
                5.1. Laptop được sử dụng đúng cách sẽ dễ dàng hơn trong việc bảo trì và nâng cấp phần cứng.</br>',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-03.jpg',
            'title' => 'Cách Giải Quyết Vấn Đề Nhiệt Độ Cao Của Laptop',
            'short_description' => 'Nhiệt độ cao có thể gây hại cho laptop. Tìm hiểu cách giải quyết vấn đề này hiệu quả.',
            'content' => 'Nhiệt độ cao có thể ảnh hưởng nghiêm trọng đến hiệu suất và tuổi thọ của laptop.</br> Dưới đây là một số cách giải quyết:</br>
            1. Vệ Sinh Quạt Tản Nhiệt</br>
                1.1. Đảm bảo quạt tản nhiệt sạch sẽ để không bị cản trở lưu thông không khí.</br>
            2. Sử Dụng Đế Tản Nhiệt</br>
                2.1. Sử dụng đế tản nhiệt giúp giảm nhiệt độ và cải thiện hiệu suất làm việc.</br>
            3. Tránh Sử Dụng Trên Bề Mặt Mềm</br>
                3.1. Không đặt laptop trên giường hoặc ghế sofa, vì điều này có thể làm tắc lỗ thông gió.</br>
            4. Kiểm Tra Phần Mềm</br>
                4.1. Đảm bảo không có phần mềm nào đang chiếm dụng quá nhiều tài nguyên hệ thống, gây nóng máy.</br>
            5. Đặt Laptop Ở Nơi Thoáng Mát</br>
                5.1. Đặt laptop ở nơi có không khí lưu thông tốt và tránh ánh nắng trực tiếp.</br>',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-04.jpg',
            'title' => '10 Mẹo Bảo Quản Điện Thoại Để Sử Dụng Bền Lâu',
            'short_description' => 'Bảo quản điện thoại đúng cách giúp thiết bị hoạt động hiệu quả và kéo dài tuổi thọ.',
            'content' => 'Để đảm bảo điện thoại của bạn luôn hoạt động tốt, dưới đây là 10 mẹo bảo quản hiệu quả:</br>
            1. Sử Dụng Ốp Lưng</br>
                1.1. Sử dụng ốp lưng để bảo vệ điện thoại khỏi va đập và trầy xước.</br>
            2. Tránh Nhiệt Độ Cao</br>
                2.1. Không để điện thoại ở nơi có nhiệt độ cao, như trên bảng điều khiển xe hơi.</br>
            3. Lau Chùi Thường Xuyên</br>
                3.1. Thường xuyên lau màn hình bằng khăn mềm để giữ cho màn hình sạch sẽ.</br>
            4. Tắt Ứng Dụng Không Cần Thiết</br>
                4.1. Tắt các ứng dụng không cần thiết để tiết kiệm pin và giảm tải cho hệ thống.</br>
            5. Sử Dụng Sạc Chính Hãng</br>
                5.1. Luôn sử dụng sạc chính hãng để đảm bảo an toàn cho pin.</br>',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-05.jpg',
            'title' => 'Cách Bảo Quản Máy Tính Bảng Để Sử Dụng Lâu Dài',
            'short_description' => 'Bảo quản máy tính bảng đúng cách giúp tăng cường hiệu suất và kéo dài tuổi thọ.',
            'content' => 'Máy tính bảng là thiết bị hữu ích trong cuộc sống hàng ngày.</br> Dưới đây là một số mẹo bảo quản:</br>
            1. Sử Dụng Bao Bì Chống Sốc</br>
                1.1. Khi di chuyển, hãy sử dụng bao bì chống sốc để bảo vệ máy tính bảng khỏi va đập.</br>
            2. Đặt Ở Nơi Thoáng Mát</br>
                2.1. Tránh để máy tính bảng ở nơi có nhiệt độ cao hoặc ẩm ướt.</br>
            3. Cập Nhật Phần Mềm</br>
                3.1. Đảm bảo cập nhật phần mềm thường xuyên để bảo mật và hiệu suất tối ưu.</br>
            4. Sử Dụng Màn Hình Bảo Vệ</br>
                4.1. Sử dụng màn hình bảo vệ để tránh trầy xước màn hình.</br>
            5. Tắt Khi Không Sử Dụng</br>
                5.1. Tắt máy tính bảng khi không sử dụng lâu để tiết kiệm pin.</br>',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-06.jpg',
            'title' => 'Cách Bảo Quản Tai Nghe Để Sử Dụng Bền Lâu',
            'short_description' => 'Bảo quản tai nghe đúng cách giúp tăng cường độ bền và chất lượng âm thanh.',
            'content' => 'Tai nghe là thiết bị không thể thiếu trong cuộc sống hiện đại.</br> Dưới đây là một số mẹo bảo quản:</br>
            1. Gập Dây Tai Nghe Đúng Cách</br>
                1.1. Gập dây tai nghe theo cách không làm hỏng dây dẫn để tránh đứt ngầm.</br>
            2. Sử Dụng Hộp Đựng</br>
                2.1. Luôn để tai nghe trong hộp đựng khi không sử dụng để bảo vệ khỏi bụi bẩn.</br>
            3. Tránh Để Ở Nơi Ẩm Ướt</br>
                3.1. Không để tai nghe ở nơi ẩm ướt, vì điều này có thể làm hỏng linh kiện điện tử.</br>
            4. Vệ Sinh Thường Xuyên</br>
                4.1. Vệ sinh tai nghe định kỳ để loại bỏ bụi bẩn và vi khuẩn.</br>
            5. Sử Dụng Với Âm Lượng Thích Hợp</br>
                5.1. Tránh nghe nhạc với âm lượng quá lớn để bảo vệ tai và kéo dài tuổi thọ của tai nghe.</br>',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('blog')->insert([
            'image' => 'blog-07.jpg',
            'title' => 'Cách Bảo Quản Dây Sạc Để Sử Dụng Lâu Dài',
            'short_description' => 'Bảo quản dây sạc đúng cách giúp tăng cường độ bền và an toàn khi sử dụng.',
            'content' => 'Dây sạc là một phần quan trọng trong việc sử dụng thiết bị điện tử. Dưới đây là một số mẹo bảo quản:
            1. Không Kéo Dây Quá Mạnh
                1.1. Tránh kéo dây sạc quá mạnh để không làm hỏng đầu nối.
            2. Gập Dây Đúng Cách
                2.1. Gập dây sạc theo cách không làm gãy hoặc đứt dây dẫn.
            3. Sử Dụng Hộp Đựng
                3.1. Để dây sạc vào hộp đựng khi không sử dụng để tránh rối.
            4. Tránh Nhiệt Độ Cao
                4.1. Không để dây sạc ở nơi có nhiệt độ cao, như gần bếp hoặc nơi có ánh nắng trực tiếp.
            5. Kiểm Tra Định Kỳ
                5.1. Kiểm tra dây sạc thường xuyên để phát hiện dấu hiệu hư hỏng và thay thế khi cần thiết.',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
