<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo sản phẩm mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .header {
            text-align: center;
            color: #333;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #3490dc;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #2779bd;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="header">Sản phẩm mới: {{ $product->product_name }}</h1>

        <p><strong>Giá:</strong> {{ $product->price }}</p>
        <p><strong>Mô tả:</strong> {{ $product->description }}</p>

        <a href="{{ url('/productDetail/' . \App\Models\Product::generateUniqueSlug($product->slug, $product->product_id)) }}" class="button">Xem sản phẩm</a>

        <p class="footer">Cảm ơn bạn đã quan tâm đến cửa hàng của chúng tôi!</p>
    </div>
</body>

</html>