<!DOCTYPE html>
<html>

<head>
    <title>Thông điệp từ khách hàng</title>
</head>

<body>
    <h1>Thông điệp từ: {{ is_string($name) ? $name : 'Tên không hợp lệ' }}</h1>
    <p>Email: {{ is_string($email) ? $email : 'Email không hợp lệ' }}</p>
    <p>Nội dung:</p>
    <p>{{ $userMessage }}</p>

</body>

</html>