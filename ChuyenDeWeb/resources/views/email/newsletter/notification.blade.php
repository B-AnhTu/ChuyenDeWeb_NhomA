@component('mail::message')
# Thông báo mới

{!! $content !!}

Trân trọng,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => config('app.url')])
Ghé thăm website
@endcomponent

Nếu bạn không muốn nhận email nữa, vui lòng [hủy đăng ký]({!! route('newsletter.unsubscribe', ['email' => encrypt($subscriber->email)]) !!})
@endcomponent