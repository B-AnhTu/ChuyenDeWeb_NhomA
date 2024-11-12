@extends('app')
@section('content')
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Giỏ hàng</h2>
                    <div class="breadcrumb__option">
                        <a href="{{asset('/')}}">Trang chủ</a>
                        <span>Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Shoping Cart Section Begin -->
<section class="shoping-cart spad">
    <div class="container">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}                                                
            <a href="{{ route('orders.my-orders') }}">Đơn hàng của tôi</a>
        </div>
        @endif
        <!-- @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif-->
        @if ($cartItems->isEmpty())
        <p>Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
        @else 
        <!-- Hiển thị thông báo -->

        <div class="row">
            <div class="col-lg-12">
                <form id="update-cart-form" method="POST" action="{{ route('cart.update') }}">
                    @csrf
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__cart__image">Hình ảnh</th>
                                    <th class="shoping__product">Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                <tr>
                                    <td class="shoping__cart__image">
                                        <a href="{{ url('/productDetail/' . $item->product->slug) }}">
                                            <img src="{{ asset('img/products/' . $item->product->image) }}" alt="">
                                        </a>
                                    </td>
                                    <td class="shoping__cart__item">
                                        <a href="{{ url('/productDetail/' . $item->product->slug) }}">
                                            <h5>{{ $item->product->product_name }}</h5>
                                        </a>
                                    </td>
                                    <td class="shoping__cart__price">
                                        {{ number_format($item->product->price) }} vnđ
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <input type="text" name="quantities[{{ $item->product->product_id }}]"
                                                    data-product-id="{{ $item->product->product_id }}"
                                                    value="{{ $item->quantity }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total cart-total-{{ $item->product->product_id }}">
                                        {{ number_format($item->product->price * $item->quantity) }} vnđ
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <span class="icon_close" data-product-id="{{ $item->product->product_id }}"></span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="shoping__cart__btns">
                            <button type="submit" class="primary-btn cart-btn cart-btn-right">
                                <span class="icon_loading"></span>
                                Cập nhật giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="shoping__checkout">
                <h5>Tổng số giỏ hàng</h5>
                <ul>
                    <li>Tổng phụ <span>{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity)) }} vnđ</span></li>
                    <li>Tổng tiền <span class="cart-total">{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity)) }} vnđ</span></li>
                </ul>
                <a href="{{ route('checkout.show') }}" class="primary-btn thanh-toan">Mua</a>
            </div>
        </div>
    </div>
    @endif
    </div>
</section>
<script src="{{ asset('js/update-cart.js') }}"></script>
<!-- Shoping Cart Section End -->
@endsection