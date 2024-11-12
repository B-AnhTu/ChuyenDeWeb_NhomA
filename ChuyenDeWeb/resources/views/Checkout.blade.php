<!-- resources/views/checkout.blade.php -->
@extends('app')
@section('content')
<section class="checkout spad">
    <div class="container">
        <div class="checkout__form">
            <h4>Chi tiết thanh toán</h4>
            <!-- @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif -->
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="checkout__input">
                            <p>Họ tên<span>*</span></p>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', $user->fullname) }}" required>
                            @error('shipping_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email<span>*</span></p>
                                    <input type="email" name="shipping_email" value="{{ old('shipping_email', $user->email) }}" required>
                                    @error('shipping_email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Số điện thoại<span>*</span></p>
                                    <input type="text" name="shipping_phone" value="{{ old('shipping_phone', $user->phone ?? '') }}" required>
                                    @error('shipping_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input">
                            <p>Địa chỉ giao hàng<span>*</span></p>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address', $user->address ?? '') }}" required>
                            @error('shipping_address')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="checkout__input">
                            <p>Ghi chú đơn hàng</p>
                            <textarea name="note">{{ old('note') }}</textarea>
                        </div>
                        <div class="checkout__input">
                            <p>Phương thức thanh toán<span>*</span></p>
                            <div class="payment-methods">
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            @error('payment_method')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Đơn hàng của bạn</h4>
                            <div class="checkout__order__products">Sản phẩm <span>Tổng</span></div>
                            <ul>
                                @if(isset($cartItems))
                                @foreach ($cartItems as $item)
                                <li>{{ $item->product->product_name }} x {{ $item->quantity }}
                                    <span>{{ number_format($item->product->price * $item->quantity) }} vnđ</span>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                            <div class="checkout__order__total">Tổng thanh toán <span>{{ number_format($total) }} vnđ</span></div>
                            <button type="submit" class="site-btn">ĐẶT HÀNG</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection