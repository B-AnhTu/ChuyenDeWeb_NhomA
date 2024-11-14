{{-- show.blade.php --}}
@extends('layouts.dashboard')
@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết đơn hàng #{{ $order->order_id }}</h1>
        <div>
            <a href="{{ route('orders.index') }}" class="btn btn-primary">
                Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Order Information --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Mã đơn hàng:</th>
                            <td>{{ $order->order_id }}</td>
                        </tr>
                        <tr>
                            <th>Ngày đặt:</th>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Tổng tiền:</th>
                            <td>{{ number_format($order->total_amount) }}đ</td>
                        </tr>
                        <tr>
                            <th>Phương thức thanh toán:</th>
                            <td>{{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' }}</td>
                        </tr>
                        <tr>
                            <th>Ghi chú:</th>
                            <td>{{ $order->note ?? 'Không có' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Customer Information --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Tên:</th>
                            <td>{{ $order->shipping_name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $order->shipping_email }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $order->shipping_phone }}</td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td>{{ $order->shipping_address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Status Update --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Cập nhật trạng thái</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Xác nhận</option>
                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Order Details --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Chi tiết sản phẩm</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
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
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ number_format($detail->price) }}đ</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price * $detail->quantity) }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Tổng cộng:</th>
                        <th>{{ number_format($order->total_amount) }}đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection