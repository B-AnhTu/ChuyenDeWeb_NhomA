@extends('app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Thông tin đơn hàng #{{ $order->order_id }}</h3>
                </div>
                <div class="card-body">
                    <!-- Hiển thị tiến trình đơn hàng -->
                    <div class="progress-track text-center">
                        <ul class="progressbar">
                            @if ($order->status == 'cancelled')
                                <li class="cancelled">Đã hủy</li>
                            @else
                                <li class="{{ in_array($order->status, ['pending', 'confirmed', 'shipping', 'completed']) ? 'active' : '' }}">Đặt hàng</li>
                                <li class="{{ in_array($order->status, ['confirmed', 'shipping', 'completed']) ? 'active' : '' }}">Xác nhận</li>
                                <li class="{{ in_array($order->status, ['shipping', 'completed']) ? 'active' : '' }}">Đang giao</li>
                                <li class="{{ $order->status == 'completed' ? 'active' : '' }}">Hoàn thành</li>
                            @endif
                        </ul>
                    </div>

                    <!-- Chi tiết đơn hàng -->
                    <div class="order-details mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Thông tin đơn hàng</h5>
                                <p>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p>Tổng tiền: {{ number_format($order->total_amount) }} vnđ</p>
                                <p>Phương thức thanh toán: {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Thông tin giao hàng</h5>
                                <p>Người nhận: {{ $order->shipping_name }}</p>
                                <p>Số điện thoại: {{ $order->shipping_phone }}</p>
                                <p>Địa chỉ: {{ $order->shipping_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
