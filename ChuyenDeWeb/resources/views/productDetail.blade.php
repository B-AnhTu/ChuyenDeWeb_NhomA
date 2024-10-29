@extends('app')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('img/banners/blackFriday.gif') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Chi tiết sản phẩm</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ asset('/') }}">Trang chủ</a>
                            <span>Chi tiết sản phẩm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large"
                                src="{{ asset('img/products/' . $product->image) }}" alt="{{ $product->product_name }}"
                                title="{{ $product->product_name }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3>{{ $product->product_name }}</h3>
                        <div class="product__details__rating">
                            <span>(18 reviews)</span>
                        </div>
                        <div class="product__details__price">{{ number_format($product->price) }} vnđ</div>
                        <p>{{ $product->description }}</p>
                        <a href="#" class="primary-btn add-to-cart rounded-pill" data-id="{{ $product->product_id }}"><i class="pe-1 fa fa-shopping-cart"></i>Thêm vào giỏ hàng</a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">Mô tả</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>{{ $product->product_name }}</h6>
                                    <p>{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- review product --}}
        <div class="container pt-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class=" rounded-2 px-3 py-2 bg-white">
                        <h2>Đánh Giá Sản Phẩm</h2>
                        <hr>
                        <div id="reviews">
                            <h5><i class="fa fa-user" aria-hidden="true"></i>
                                tên người dùng
                            </h5>
                            <div class="d-flex">
                                <p class="pe-5">nội dung</p>
                                <p>ngày tháng năm</p>
                            </div>
                        </div>
                        <form id="review-form">
                            <div class="form-group mb-3">
                                <label class="pb-2" for="review-text">Nội dung đánh giá:</label>
                                <textarea class="form-control" id="review-text" rows="5" placeholder="Viết đánh giá của bạn..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary shadow-0" id="submit-review">Gửi đánh
                                giá</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->
@endsection
