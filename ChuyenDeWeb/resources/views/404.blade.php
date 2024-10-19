<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin: 0;
        }

        .container {
            padding: 20px;
        }

        h1 {
            font-size: 6rem;
            color: #dc3545;
        }

        h2 {
            font-size: 2rem;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="display-1">404</h1>
        <h2>Không tìm thấy trang</h2>
        <p>Xin lỗi, trang bạn tìm kiếm không tồn tại.</p>
        <a href="{{ url('/') }}" class="btn">Quay lại trang chủ</a>
    </div>
</body>

</html>
