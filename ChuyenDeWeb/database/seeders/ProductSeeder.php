<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'product_name' => 'Samsung Galaxy A35 5G 8GB 128GB',
                'manufacturer_id' => 2,
                'category_id' => 1,
                'price' => 7990000,
                'image' => 'samsunga35.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 19,
                'product_view' => 0,
                'description' => 'Samsung Galaxy A35 sở hữu những tính năng mang tính đột phá, vượt trội hơn hẳn những mẫu điện thoại thông thường. Với thiết kế ngoại hình ấn tượng cùng sự mạnh mẽ của hiệu năng tới từ con chip Exynos 1380 đi kèm với bộ vi xử lý đồ hoạ ấn tượng, sản phẩm Samsung Galaxy A mới này được các chuyên gia đánh giá là rất có tiềm năng so với những chiếc máy cùng phân khúc.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Samsung Galaxy S24 Ultra 12GB 256GB',
                'manufacturer_id' => 2,
                'category_id' => 1,
                'price' => 28490000,
                'image' => 'samsungs24ultra.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 12,
                'description' => 'Samsung S24 Ultra là siêu phẩm smartphone đỉnh cao mở đầu năm 2024 đến từ nhà Samsung với chip Snapdragon 8 Gen 3 For Galaxy mạnh mẽ, công nghệ tương lai Galaxy AI cùng khung viền Titan đẳng cấp hứa hẹn sẽ mang tới nhiều sự thay đổi lớn về mặt thiết kế và cấu hình.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'iPhone 15 Pro Max 256GB | Chính hãng VN/A',
                'manufacturer_id' => 1,
                'category_id' => 1,
                'price' => 29990000,
                'image' => 'iphone15.jpg',
                'stock_quantity' => 25,
                'sold_quantity' => 11,
                'product_view' => 0,
                'description' => 'iPhone 15 Pro Max thiết kế mới với chất liệu titan chuẩn hàng không vũ trụ bền bỉ, trọng lượng nhẹ, đồng thời trang bị nút Action và cổng sạc USB-C tiêu chuẩn giúp nâng cao tốc độ sạc. Khả năng chụp ảnh đỉnh cao với camera chính 48MP, UltraWide 12MP và telephoto zoom quang học 5x.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'iPhone Xs 256GB',
                'manufacturer_id' => 1,
                'category_id' => 1,
                'price' => 7590000,
                'image' => 'iphonexs.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 18,
                'product_view' => 12,
                'description' => 'iPhone Xs Max 256GB cũ mang nhiều ưu điểm vượt trội hơn so với các phiên bản iPhone trước đó, từ tính năng, cấu hình cho đến giá.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Xiaomi 13T 12GB 256GB',
                'manufacturer_id' => 3,
                'category_id' => 1,
                'price' => 10290000,
                'image' => 'xiaomi13t.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Xiaomi 13T đem tới trải nghiệm siêu mượt mà với chipset MediaTek Dimensity 8200-Ultra và màn hình AMOLED tần số quét 144Hz.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Xiaomi Redmi Note 11',
                'manufacturer_id' => 3,
                'category_id' => 1,
                'price' => 3890000,
                'image' => 'xiaomi_redmi_note_11.jpg',
                'stock_quantity' => 100,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Xiaomi Redmi Note 11 với màn hình 6.43 inches AMOLED, chip Snapdragon 680 và camera 50MP đáp ứng tốt nhu cầu nhiếp ảnh.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Huawei P50 Pro',
                'manufacturer_id' => 4,
                'category_id' => 1,
                'price' => 15000000,
                'image' => 'huawei_p50_pro.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Huawei P50 Pro với khả năng chụp ảnh chuyên nghiệp, được xếp top 1 bảng xếp hạng điện thoại có camera tốt nhất thế giới.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Huawei P20',
                'manufacturer_id' => 4,
                'category_id' => 1,
                'price' => 3500000,
                'image' => 'huawei_p20_pro.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Với việc liên tục cải tiến sản phẩm, bổ sung nhiều công nghệ hiện đại cả về phần cứng lẫn phần mềm, hãng điện thoại Huawei đang ngày càng khẳng định vị thế của một trong những nhà sản xuất smartphone hàng đầu thế giới, mà điện thoại Huawei P20 chính là chiếc điện thoại minh chứng cho điều đó',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Google Pixel 4',
                'manufacturer_id' => 5,
                'category_id' => 1,
                'price' => 3500000,
                'image' => 'google_pixel_4.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 15,
                'product_view' => 0,
                'description' => 'Hằng năm thì tín đồ yêu công nghệ vẫn luôn chờ đợi vào sự ra mắt của những sản phẩm phần cứng mới tới từ Google và năm nay với Google Pixel 4 thì một lần nữa Google khẳng định được vị thế của mình với chất lượng chụp hình vượt trội.',
                'created_at' => now(),
            ],

            [
                'product_name' => 'Google Pixel 8 Pro',
                'manufacturer_id' => 5,
                'category_id' => 1,
                'price' => 10530000,
                'image' => 'google_pixel_8_pro.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 10,
                'description' => 'Google Pixel 8 Pro nổi bật với chip Tensor G3 và màn hình OLED 6.7inch 120Hz. Cụm camera 50MP cho chất lượng ảnh ấn tượng.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Smart Tivi Samsung 4K 50 inch 50BU8000',
                'manufacturer_id' => 2,
                'category_id' => 3,
                'price' => 8400000,
                'image' => 'samsungtv.png',
                'stock_quantity' => 50,
                'sold_quantity' => 11,
                'product_view' => 0,
                'description' => 'Smart Tivi Samsung 4K Crystal UHD 50 inch với kiểu dáng sang trọng, chân đế tinh tế và viền mỏng tạo cảm giác đắm chìm cho người xem.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Smart Tivi QLED 4K Samsung 75 Inch QA75Q70B',
                'manufacturer_id' => 2,
                'category_id' => 3,
                'price' => 17490000,
                'image' => 'samsungtv4k.jpg',
                'sold_quantity' => 0,
                'stock_quantity' => 50,
                'product_view' => 19,
                'description' => 'Bùng nổ trong thiết kế với màn hình Airslim 3 cạnh không viền hoàn hảo giúp khai phóng tầm nhìn mở ra không gian giải trí sống động hơn.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Xiaomi TV A 32 (L32M8-P2SEA) 2023 Series',
                'manufacturer_id' => 3,
                'category_id' => 3,
                'price' => 3690000,
                'image' => 'xiaomitva32.png',
                'sold_quantity' => 17,
                'stock_quantity' => 100,
                'product_view' => 0,
                'description' => 'Điều khiển bằng giọng nói, Chiếu điện thoại lên TV (không dây), Kết nối loa qua Bluetooth, Trợ lý ảo Google Assistant.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Xiaomi Smart TV EA55 (L55MA-EA) 2023 Series',
                'manufacturer_id' => 3,
                'category_id' => 3,
                'price' => 6990000,
                'image' => 'xiaomitvea55.png',
                'sold_quantity' => 0,
                'stock_quantity' => 30,
                'product_view' => 0,
                'description' => 'Điều khiển bằng giọng nói, Chiếu điện thoại lên TV (không dây), Kết nối loa qua Bluetooth, Trợ lý ảo Google Assistant.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Huawei Smart Screen V TV',
                'manufacturer_id' => 4,
                'category_id' => 3,
                'price' => 27790000,
                'image' => 'huaweitv.jpg',
                'sold_quantity' => 0,
                'stock_quantity' => 20,
                'product_view' => 0,
                'description' => 'Huawei Smart Screen V TV có thiết kế viền siêu mỏng, màn hình Ultra HD 4K với tỷ lệ khung hình 16:9, độ bao phủ màu 92% DCI-P3 và độ sáng tối đa lên đến 900 nits.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Huawei Smart Screen V55i',
                'manufacturer_id' => 4,
                'category_id' => 3,
                'price' => 32990000,
                'image' => 'huaweitv4k.jpg',
                'sold_quantity' => 0,
                'stock_quantity' => 25,
                'product_view' => 10,
                'description' => 'Huawei cũng đã giới thiệu thêm một số dòng sản phẩm mới khá thú vị, một trong số đó là mẫu Smart TV mới có tên Huawei Smart Screen V55i.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Google TV 4K Streaming Box',
                'manufacturer_id' => 5,
                'category_id' => 3,
                'price' => 35990000,
                'image' => 'googletv.jpg',
                'sold_quantity' => 0,
                'stock_quantity' => 50,
                'product_view' => 0,
                'description' => 'Hãy thưởng thức các kênh số yêu thích của bạn thông qua Thiết bị Phát trực tuyến 4K của chúng tôi.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Chromecast with Google TV (4K)',
                'manufacturer_id' => 5,
                'category_id' => 3,
                'price' => 40990000,
                'image' => 'googletv4k.jpg',
                'sold_quantity' => 0,
                'stock_quantity' => 50,
                'product_view' => 0,
                'description' => 'Sử dụng giọng nói của bạn. Nhấn nút Google Assistant trên điều khiển từ xa và yêu cầu tìm kiếm các chương trình TV cụ thể, hoặc theo tâm trạng, thể loại, diễn viên và nhiều hơn nữa. Hỏi Google và nói, “Tôi nên xem gì?” Và sử dụng điều khiển từ xa để điều chỉnh âm lượng, chuyển đổi đầu vào, phát nhạc và nhận câu trả lời trên màn hình.',
                'created_at' => now(),
            ],

            [
                'product_name' => 'Samsung Galaxy Tab S9 FE 5G 6GB 128GB',
                'manufacturer_id' => 2,
                'category_id' => 2,
                'price' => 9390000,
                'image' => 'samsungtab9.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 14,
                'product_view' => 0,
                'description' => ' là dòng máy tính bảng xịn sò nhất hiện nay. Cùng với thiết kế gọn nhẹ, màn hình sắc nét cũng như hiệu năng cực đỉnh cùng bộ vi xử lý Snapdragon 8 Gen 2 cao cấp nhất của nhà Qualcomm, Samsung Galaxy Tab S9 Series nắm chắc vị trí thống lĩnh thị trường máy tính bảng. Đặc biệt cả ba thiết bị bao gồm Galaxy Tab S9, Galaxy Tab S9 Plus và Galaxy Tab S9 Ultra đều được hỗ trợ đáp ứng cây bút Spen thần thánh của hãng cũng như bàn phím tiện lợi. Điều này giúp cho người dùng sử dụng tiện lợi với tất cả mục đích từ những thao tác cơ bản, giải trí đến cả làm việc hoặc chơi game nặng một cách thoải mái nhất có thể.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Samsung Galaxy Tab S9 Ultra 12GB 256GB',
                'manufacturer_id' => 2,
                'category_id' => 2,
                'price' => 21390000,
                'image' => 'samsungtabultra.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 10,
                'description' => ' là dòng máy tính bảng xịn sò nhất hiện nay. Cùng với thiết kế gọn nhẹ, màn hình sắc nét cũng như hiệu năng cực đỉnh cùng bộ vi xử lý Snapdragon 8 Gen 2 cao cấp nhất của nhà Qualcomm, Samsung Galaxy Tab S9 Series nắm chắc vị trí thống lĩnh thị trường máy tính bảng. Đặc biệt cả ba thiết bị bao gồm Galaxy Tab S9, Galaxy Tab S9 Plus và Galaxy Tab S9 Ultra đều được hỗ trợ đáp ứng cây bút Spen thần thánh của hãng cũng như bàn phím tiện lợi. Điều này giúp cho người dùng sử dụng tiện lợi với tất cả mục đích từ những thao tác cơ bản, giải trí đến cả làm việc hoặc chơi game nặng một cách thoải mái nhất có thể.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Apple iPad Pro 10.5 Cellular 64Gb',
                'manufacturer_id' => 1,
                'category_id' => 2,
                'price' => 24290000,
                'image' => 'ipadpro10.5.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' Apple vẫn giữ ngôn ngữ thiết kế từ xa xưa tới nay, nên phiên bản 10.5 inch cũng không có gì khác lắm với những người tiền nhiệm còn lại. Tuy không mới nhưng đây vẫn là một thiết kế vượt thời gian và rất sang trọn',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Máy tính bảng iPad Air 5 M1 WiFi 64GB',
                'manufacturer_id' => 1,
                'category_id' => 2,
                'price' => 14290000,
                'image' => 'ipadair5.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' iPad Air 5 M1 WiFi 64 GB có thiết kế phẳng ở 4 cạnh, mặt sau được làm từ nhôm với nhiều màu sắc tươi trẻ. Đặc biệt, năm nay Apple đã bổ sung màu tím cho dòng iPad Air, màu sắc này sẽ gây ấn tượng mạnh khi chúng ta cầm máy sử dụng. Màn hình của máy cũng được làm phẳng với kích thước 10.9 inch.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Máy tính bảng Xiaomi Redmi Pad SE (4GB/128GB)',
                'manufacturer_id' => 3,
                'category_id' => 2,
                'price' => 4990000,
                'image' => 'xiaomipadse.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' Máy tính bảng Xiaomi Redmi Pad SE được thiết kế với sự hiện đại và tinh tế theo kiểu vuông vắn bắt trend. Sự kết hợp hoàn hảo giữa mặt lưng và bộ khung làm phẳng không chỉ tạo nên một cái nhìn hiện đại mà còn mang lại cảm giác sang trọng, đầy vẻ thanh lịch',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Máy tính bảng Xiaomi Pad 6',
                'manufacturer_id' => 3,
                'category_id' => 2,
                'price' => 2090000,
                'image' => 'xiaomipad6.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' Xiaomi Pad 6 là một chiếc máy tính bảng có thiết kế tinh tế và cao cấp nhờ vào lớp hoàn thiện kim loại ở mặt sau và các cạnh. Máy tính bảng này có 3 màu: Graphite Grey (xám), Mist Blue (xanh) và Gold (vàng). Với khối lượng 490 gram, Xiaomi Pad 6 nhẹ hơn người tiền nhiệm của nó khoảng 20 gram nên cho cảm giác cầm nắm cũng được thoải mái hơn.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Huawei MatePad 11',
                'manufacturer_id' => 4,
                'category_id' => 2,
                'price' => 8990000,
                'image' => 'huaweimatepad.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' Huawei MatePad 11 sở hữu một thiết kế sang trọng và tinh tế. Nhìn tổng thể, theo mình thiết kế của Huawei MatePad 11 mang một chút hơi hướng giống những chiếc iPad, tuy nhiên thay vì các cạnh được vát phẳng như trên iPad thì chiếc máy có các cạnh được làm cong nhẹ ở đều bốn cạnh, cực kỳ phù hợp cho những bạn ưa thích kiểu dáng mềm mại, không những vậy thì trải nghiệm cầm nắm của bạn cũng sẽ rất thoải mái',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Máy tính bảng Huawei MediaPad M2 8.0',
                'manufacturer_id' => 4,
                'category_id' => 2,
                'price' => 1990000,
                'image' => 'huaweimatepad.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' Huawei MediaPad M2 8.0 là thế hệ tablet thứ 2 tiếp nối người đi trước cùng tên, với hữu màn hình 8.0 inch full HD rất sắc nét. Huawei đã đem đến nhiều điều bất ngờ trên thiết bị, công nghệ âm thanh vòm cao cấp cùng thiết kế khung kim loại chính là hai điểm ấn tượng trên máy. Chip xử lý do chính tay hãng sản xuất do đó giá thành tốt nhưng vẫn đảm bảo hiệu năng ấn tượng',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Google Pixel Tablet (6GB - 128GB)',
                'manufacturer_id' => 5,
                'category_id' => 2,
                'price' => 5200000,
                'image' => 'googlepixceltablet.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' Pixel Tablet sẽ cho bạn trải nghiệm Android tuyệt vời nhất trên máy tính bảng", trích lời khẳng định của Google. Thật sự là như vậy khi chúng ta có thể thấy giao diện trên Pixel Tablet đã được Google tối ưu triệt để.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Pixel Tablet 2',
                'manufacturer_id' => 5,
                'category_id' => 2,
                'price' => 3290000,
                'image' => 'pixeltablet2.jpeg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Google dường như đang chuẩn bị mọi thứ để ra mắt Pixel Tablet 2. Trang tin MySmartPrice phát hiện rằng chiếc máy tính bảng thuộc dòng Pixel sắp tới bắt đầu xuất hiện trên cơ sở dữ liệu Geekbench với tên mã tangorpro',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Tai nghe Bluetooth AirPods Pro (2nd Gen) MagSafe Charge Apple MQD83',
                'manufacturer_id' => 1,
                'category_id' => 4,
                'price' => 5990000,
                'image' => 'AppleHeadphones1.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Tai nghe AirPods Pro 2 được tích hợp con chip Apple H2 có thể mang lại dải âm rõ ràng, chi tiết với khả năng hiển thị từng nốt cao và âm bass sâu một cách đầy đủ. Mọi âm thanh thông qua Airpods Pro 2 đều sống động hơn bao giờ hết.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Tai nghe Bluetooth AirPods 3 Lightning Charge Apple MPNY3',
                'manufacturer_id' => 1,
                'category_id' => 4,
                'price' => 3990000,
                'image' => 'AppleHeadphones2.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Tai nghe Bluetooth AirPods 3 Lightning Charge Apple MPNY3 sở hữu thiết kế gọn nhẹ, màu sắc trang nhã cùng nhiều công nghệ hiện đại đang chờ đón các iFan như: Kết nối Bluetooth, Adaptive EQ, Chip Apple H1,...',
                'created_at' => now(),
            ],

            [
                'product_name' => 'Tai nghe Bluetooth True Wireless Samsung Galaxy Buds2 Pro',
                'manufacturer_id' => 2,
                'category_id' => 4,
                'price' => 4990000,
                'image' => 'SamsungHeadphones1.png',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Tai nghe Bluetooth True Wireless Samsung Galaxy Buds2 Pro sở hữu loa 2 hai chiều, với một loa trầm và một loa tweeter, giúp người dùng trải nghiệm đa dạng thể loại nhạc, với chất âm hòa quyện với nhau.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Tai nghe Bluetooth Samsung Galaxy Buds Pro 2',
                'manufacturer_id' => 2,
                'category_id' => 4,
                'price' => 4990000,
                'image' => 'SamsungHeadphones2.png',
                'stock_quantity' => 50,
                'sold_quantity' => 13,
                'product_view' => 2,
                'description' => 'Tai nghe Bluetooth Samsung Galaxy Buds Pro 2 với nhiều nâng cấp và cải tiến, hứa hẹn mang lại cho người dùng những trải nghiệm dùng vượt trội',
                'created_at' => now(),
            ],

            [
                'product_name' => 'Tai nghe Bluetooth True Wireless Xiaomi Redmi Buds 5',
                'manufacturer_id' => 3,
                'category_id' => 4,
                'price' => 1790000,
                'image' => 'XiaomiHeadphones1.png',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Tai nghe không dây Xiaomi Redmi Buds 5 Pro sở hữu chất âm Hi-Fi sống động, được tinh chỉnh bởi Xiaomi để mang lại trải nghiệm nghe nhạc tuyệt vời.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Tai nghe Bluetooth True Wireless Xiaomi Buds 3',
                'manufacturer_id' => 3,
                'category_id' => 4,
                'price' => 990000,
                'image' => 'XiaomiHeadphones2.png',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 10,
                'description' => 'Tai nghe Xiaomi Redmi Buds 5 trang bị tính năng chống ồn chủ động, cùng công nghệ kết nối Bluetooth 5.3 ổn định. Với buồng âm thanh độc lập, cuộn dây quấn bằng đồng dài và màng loa titan polymer 12.4m giúp âm thanh được phát ra trong trẻo',
                'created_at' => now(),
            ],

            [
                'product_name' => 'Tai nghe Bluetooth True Wireless HUAWEI FreeClip',
                'manufacturer_id' => 4,
                'category_id' => 4,
                'price' => 3990000,
                'image' => 'HuaweiHeadphones1.png',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Huawei Freeclip là tai nghe không dây với thiết kế C-bridge ấn tượng cùng với Driver nam châm kép 10,8 mm mang lại trải nghiệm âm thanh vượt trội.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Tai nghe Bluetooth True Wireless Huawei Freebuds 5',
                'manufacturer_id' => 4,
                'category_id' => 4,
                'price' => 2250000,
                'image' => 'HuaweiHeadphones2.png',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Huawei Freebuds 5 sở hữu công nghệ âm thanh vượt trội với trình điều khiển 11mm siêu từ tính, công nghệ tăng âm trầm cùng chứng nhận âm thanh HWA và Hi-Res. ',
                'created_at' => now(),
            ],

            [
                'product_name' => 'Tai nghe Bluetooth Pixel Buds Pro',
                'manufacturer_id' => 5,
                'category_id' => 4,
                'price' => 3390000,
                'image' => 'GoogleHeadphones1.png',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Pixel Buds Pro đối trọng thực sự của AirPods Pro Tai nghe của Google có chống ồn chủ động, kết nối dễ dàng và thoải mái khi đeo trên tai. Thiết kế bo tròn nhỏ gọn và thoải mái khi đeo, bề mặt cảm ứng và có microphone ở xung quanh tai nghe.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Tai nghe Bluetooth Pixel Buds A-Series',
                'manufacturer_id' => 5,
                'category_id' => 4,
                'price' => 1600000,
                'image' => 'GoogleHeadphones2.png',
                'stock_quantity' => 50,
                'sold_quantity' => 13,
                'product_view' => 0,
                'description' => 'Google Pixel Buds A là một trong những tai nghe không dây hàng đầu của Google, mang đến trải nghiệm tuyệt vời cho người dùng với thiết kế hiện đại, khả năng kết nối Bluetooth ổn định, và âm thanh chất lượng cao.',
                'created_at' => now(),
            ],

            [
                'product_name' => 'Loa Apple Home Pod',
                'manufacturer_id' => 1,
                'category_id' => 5,
                'price' => 7112000,
                'image' => 'applehomepod.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 17,
                'description' => 'Nếu bạn hay nghe nhạc Apple Music, loa thông minh HomePod chắc chắn sẽ là sự lựa chọn hoàn hảo dành cho bạn. Với HomePod, bạn sẽ có những trải nghiệm âm nhạc tốt nhất. HomePod được trang bị khá nhiều tính năng sở hữu thiết kế tinh tế chắc chắn sẽ là điểm nhấn cho không gian làm việc hay căn phòng của bạn. Ngoài ra, HomePod tích hợp vi xử lý A8, tích hợp sẵn ứng dụng Apple Music, trợ lý ảo Siri chắc chắn sẽ mang đến cho bạn những trải nghiệm thú vị nhất.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa Apple Home Pod Mini',
                'manufacturer_id' => 1,
                'category_id' => 5,
                'price' => 3990000,
                'image' => 'applehomepodmini.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 11,
                'product_view' => 0,
                'description' => 'Apple đã không ngừng nghiên cứu cho ra mắt những thiết bị thông minh với những tính năng vượt trội mà người dùng không thể nào lường trước. Thời gian gần đây nhất, tháng 10 năm 2020 Apple đã cho ra mắt sản phẩm mới đó là loa Homepod Mini nhiều tính năng hiện đại.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa Samsung Level Box Mini',
                'manufacturer_id' => 2,
                'category_id' => 5,
                'price' => 1800000,
                'image' => 'loasamsunglevelbox.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 12,
                'description' => 'Trong các phụ kiện Samsung thì loa là dòng đồ chơi khá được nhiều bạn trẻ yêu thích thì tính năng cũng như thiết kế của nó. Với một sản phẩm có thể nghe nhạc, đàm thoại mà trọng lượng nhẹ, thiết kế đẹp, cùng nhiều tính năng hấp dẫn thì chỉ có thể là loa buetooth Samsung level Box Mini chính hãng.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa tháp Samsung MX-T70',
                'manufacturer_id' => 2,
                'category_id' => 5,
                'price' => 7990000,
                'image' => 'loasamsungmx-t70.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => ' Loa tháp Samsung MX-T70 với công suất lên đến 1500W hứa hẹn sẽ thổi bùng các bữa tiệc của bạn với những giai điệu sôi động, cuồng nhiệt. Tính năng Bass Booster cho những dải âm thanh trầm. Thiết kế của loa có 2 mặt cho âm thanh hướng ra nhiều phía hơn, khiến trải nghiệm chân thật hơn.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa Xiaomi Classical',
                'manufacturer_id' => 3,
                'category_id' => 5,
                'price' => 1590000,
                'image' => 'xiaomiclassical.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 14,
                'product_view' => 0,
                'description' => 'Với thiết kế nhỏ gọn giúp bạn có thể mang bất cứ đâu và loa có thể phát nhạc liên tục đến 12 giờ, mặt loa được hướng lên trên giúp âm thánh khi phát ra rộng và vang hơn. Có 1 dãi đèn phía dưới logo Mi tạo nên vẻ sang trọng cho thiết bị. Chip âm thanh được tích hợp trong loa cho chất âm ấm, trung thực, âm thanh trong và rõ khi có mở max volume vẫn rất tuyệt vời. Các phím bấm như âm lượng, tắt mở loa, kết nối được Xiaomi dời xuống dưới giúp giấu được các phím cứng này tạo nên sự liền mạch giúp sản phẩm nhìn sang trọng hơn các dòng loa Bluetooth khác.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa Bluetooth mini Xiaomi 2020',
                'manufacturer_id' => 3,
                'category_id' => 5,
                'price' => 220000,
                'image' => 'loaxiaomibluetoothmini.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 13,
                'description' => 'Không thể phủ nhận sức hút của các thiết bị loa bluetooth mini đối với người dùng công nghệ. Bởi thiết kế mini luôn mang đến sự tinh tế, sang trọng cũng như tính di động cao. Nếu bạn hiện đang có ý định tìm cho mình một chiếc loa mini với vẻ ngoài trẻ trung cùng chất âm ấn tượng thì loa Bluetooth mini Xiaomi 2020 dưới đây chính là một sự lựa chọn tuyệt vời mà bạn không nên bỏ lỡ.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa Bluetooth Huawei Sound Joy',
                'manufacturer_id' => 4,
                'category_id' => 5,
                'price' => 2690000,
                'image' => 'loahuaweisoundjoy.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Sản phẩm loa Bluetooth HUAWEI Sound Joy có chiều cao và chiều rộng lần lượt là 202 và 73mm cùng trọng lượng nặng khoảng 680g. Với kích thước vô cùng gọn nhẹ, thiết bị loa sẽ luôn sẵn sàng ở bên bạn trong mọi chuyến du lịch, chỉ cần nắm chặt dây đeo là bạn đã có đủ mọi thứ cho một cuộc phiêu lưu thú vị trong âm nhạc. ',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa bluetooth Huawei honor AM08',
                'manufacturer_id' => 4,
                'category_id' => 5,
                'price' => 400000,
                'image' => 'loahuaweihonoram08.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Huawei AM08 được đánh giá cao về chất lượng âm thanh trung thực, sống động tràn đầy sức sống và phù hợp với diện tích phòng nhỏ. Sản phẩm đáp ứng những dài tần số cực rộng với những cung bậc âm thanh trầm, trung, cao đều rõ ràng đem lại âm thanh sống động.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa thông minh Google Home Mini',
                'manufacturer_id' => 5,
                'category_id' => 5,
                'price' => 650000,
                'image' => 'loagooglehomemini.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 0,
                'description' => 'Loa Google Home Mini là một chiếc loa thông minh kiêm trợ lý ảo Google Assistant. Nó cho phép bạn tương tác, ra lệnh và điều khiển các thiết bị nhà thông minh. So với đàn anh “Google Home”, sản phẩm này ra đời với mục đích chính – phủ sóng Google Assistant đến mọi nơi trong căn nhà bạn.',
                'created_at' => now(),
            ],
            [
                'product_name' => 'Loa Google Home tích hợp trợ lý ảo',
                'manufacturer_id' => 5,
                'category_id' => 5,
                'price' => 1650000,
                'image' => 'loagooglehome.jpg',
                'stock_quantity' => 50,
                'sold_quantity' => 0,
                'product_view' => 10,
                'description' => 'Google Home là một chiếc loa wifi kiêm chức năng điều khiển trung tâm nhà thông minh, tích hợp trợ lý ảo Google Assistant cho phép bạn điều khiển bằng giọng nói. Bạn cũng có thể sử dụng như thiết bị phát/stream các bộ phim, nghe nhạc từ các dịch vụ trực tuyến.',
                'created_at' => now(),
            ]
        ];

        foreach ($products as $product) {
            // Tạo sản phẩm
            $newProduct = Product::create($product);
            // Tạo slug từ product_name và ID của sản phẩm
            $newProduct->slug = Product::generateUniqueSlug($newProduct->product_name, $newProduct->product_id);
            $newProduct->save();
        }
    }
}
