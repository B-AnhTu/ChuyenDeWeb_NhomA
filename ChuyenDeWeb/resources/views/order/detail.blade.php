<!-- resources/views/order/detail.blade.php -->
@extends('app')
@section('content')
<div class="container">
    <h2>Chi tiết đơn hàng #{{ $order->order_id }}</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Thông tin đơn hàng</h4>
            <p>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p>Trạng thái:
            <div class="order-status">
                @switch($order->status)
                @case('pending')
                <div class="alert alert-warning">
                    Đơn hàng của bạn đang chờ xử lý. Chúng tôi sẽ sớm xác nhận đơn hàng.
                </div>
                @break
                @case('confirmed')
                <div class="alert alert-info">
                    Đơn hàng đã được xác nhận và đang được chuẩn bị.
                </div>
                @break
                @case('shipping')
                <div class="alert alert-primary">
                    Đơn hàng đang được giao đến bạn.
                </div>
                @break
                @case('completed')
                <div class="alert alert-success">
                    Đơn hàng đã được giao thành công.
                </div>
                @break
                @case('cancelled')
                <div class="alert alert-danger">
                    Đơn hàng đã bị hủy.
                </div>
                @break
                @endswitch
            </div>
            </p>
            <p>Phương thức thanh toán: {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' }}</p>
        </div>
        <div class="col-md-6">
            <h4>Thông tin giao hàng</h4>
            <p>Người nhận: {{ $order->shipping_name }}</p>
            <p>Số điện thoại: {{ $order->shipping_phone }}</p>
            <p>Email: {{ $order->shipping_email }}</p>
            <p>Địa chỉ: {{ $order->shipping_address }}</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h4>Sản phẩm đặt mua</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderDetails as $detail)
                        <tr>
                            <td>
                                <img src="{{ asset('img/products/' . $detail->product->image) }}" alt="" style="width: 50px">
                                {{ $detail->product->product_name }}
                            </td>
                            <td>{{ number_format($detail->price) }} vnđ</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->price * $detail->quantity) }} vnđ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td><strong>{{ number_format($order->total_amount) }} vnđ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection