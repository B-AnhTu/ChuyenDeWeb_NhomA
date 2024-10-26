@component('mail::message')
# Xác nhận đăng ký nhận tin

Chào {{ $subscriber->name ?? 'bạn' }},

Cảm ơn bạn đã đăng ký nhận tin tức từ chúng tôi.
Vui lòng nhấn vào nút bên dưới để xác nhận email của bạn:

@component('mail::button', ['url' => $verificationUrl])
Xác nhận đăng ký
@endcomponent

Nếu bạn không thực hiện đăng ký này, bạn có thể bỏ qua email này.

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent