<!-- resources/views/order/tracking-form.blade.php -->
@extends('app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tra cứu đơn hàng</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.track') }}">
                        @csrf
                        <div class="form-group">
                            <label>Mã đơn hàng</label>
                            <input type="number" name="order_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email đặt hàng</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="primary-btn">Tra cứu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection