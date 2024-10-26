@extends('app')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/banners/banner4.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Sản phẩm yêu thích</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ asset('/') }}">Trang chủ</a>
                            <span>Sản phẩm yêu thích</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    <div class="container mt-5 pt-5">
        @if (session('message'))
            <div class="alert alert-warning">{{ session('message') }}</div>
        @endif

        @if ($likedProducts->isEmpty())
            <p class="text-center">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
        @else
            <div class="row">
                @foreach ($likedProducts as $likedProduct)
                    @php $product = $likedProduct->product; @endphp
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="{{ asset('img/products/' . $product->image) }}" class="card-img-top img-fluid" alt="{{ $product->product_name }}" style="height: 350px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><a class="text-black"
                                    href="{{ url('/productDetail/' . $product->slug) }}">{{ $product->product_name }}</a>
                            </h5>
                                <p class="card-text">{{ number_format($product->price) }} VNĐ</p>
                                <p><i class="fa fa-eye"></i> {{ $product->product_view }}</p>

                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary add-to-cart" data-id="{{ $product->product_id }}">
                                        <i class="fa fa-shopping-cart px-1"></i>Thêm vào giỏ hàng
                                    </button>
                                    <button class="btn btn-danger ffa-heart" data-id="{{ $product->product_id }}">
                                        <i class="fa fa-heart liked"></i> Bỏ thích
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
