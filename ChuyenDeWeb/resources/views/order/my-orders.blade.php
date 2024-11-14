<!-- resources/views/order/my-orders.blade.php -->
@extends('app')
@section('content')


<div class="container">
    <h2>Đơn hàng của tôi</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->order_id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($order->total_amount) }} vnđ</td>
                    <td>
                        @switch($order->status)
                        @case('pending')
                        <span class="badge badge-warning">Chờ xử lý</span>
                        @break
                        @case('processing')
                        <span class="badge badge-info">Đã xác nhận</span>
                        @break
                        @case('shipping')
                        <span class="badge badge-primary">Đang giao hàng</span>
                        @break
                        @case('completed')
                        <span class="badge badge-success">Hoàn thành</span>
                        @break
                        @case('cancelled')
                        <span class="badge badge-danger">Đã hủy</span>
                        @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('orders.detail', $order->order_id) }}" class="btn btn-sm btn-info">Chi tiết</a>
                        @if($order->status == 'pending')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">Hủy</button>
                        </form>
                        @endif
                        @if($order->status == 'shipping')
                        <form action="{{ route('orders.confirm-received', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Đã nhận hàng</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>

@endsection