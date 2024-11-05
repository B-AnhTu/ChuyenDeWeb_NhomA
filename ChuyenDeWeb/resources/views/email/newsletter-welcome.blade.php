<!DOCTYPE html>
<html>
<head>
    <title>Chào mừng đến với bản tin</title>
</head>
<body>
    <h2>Xin chào {{ $name ? $name : 'bạn' }}!</h2>
    
    <p>Cảm ơn bạn đã đăng ký nhận bản tin của chúng tôi.</p>
    
    <p>Chúng tôi sẽ gửi cho bạn những thông tin mới nhất về:</p>
    <ul>
        <li>Sản phẩm mới</li>
        <li>Khuyến mãi đặc biệt</li>
        <li>Tin tức và cập nhật từ cửa hàng</li>
    </ul>
    
    <p>Email đăng ký của bạn là: {{ $email }}</p>
    
    <p>Trân trọng,<br>
    Đội ngũ của chúng tôi</p>
</body>
</html>