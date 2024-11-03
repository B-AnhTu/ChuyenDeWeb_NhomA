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
                        <span>({{ $product->reviews->where('status', 1)->count() }} đánh giá)</span>
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
    {{-- Phần đánh giá sản phẩm --}}
    <div class="container pt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="rounded-2 px-3 py-2 bg-white">
                    <h2>Đánh Giá Sản Phẩm</h2>
                    <div id="review-message" class="alert" style="display: none;"></div>
                    @auth
                    <form id="review-form" class="mt-4">
                        <div class="form-group mb-3">
                            <label class="pb-2" for="review-text">Nội dung đánh giá:</label>
                            <textarea class="form-control" id="review-text" rows="5" placeholder="Viết đánh giá của bạn..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary shadow-0" id="submit-review">
                            Gửi đánh giá
                        </button>
                    </form>
                    <div id="recent-review" class="mt-3"></div>
                    @else
                    <div class="alert alert-info">
                        Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để gửi đánh giá
                    </div>
                    @endauth
                    <!-- Comment Display Section -->
                    <div class="card my-4">
                        <h5 class="card-header">Bình luận:</h5>
                        <div class="card-body">
                            @if ($product->reviews && $product->reviews->count() > 0)
                            @foreach($product->reviews->sortByDesc('created_at') as $review) {{-- Sắp xếp bình luận --}}
                            @if ($review->status == 1) {{-- Chỉ hiển thị bình luận đã duyệt --}}
                            <div class="review">
                                <h6>{{ $review->user->fullname }}
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                </h6>
                                <p>{{ $review->comment }}</p>
                            </div>
                            <hr class="my-3">
                            @endif
                            @endforeach
                            @else
                            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<!-- Product Details Section End -->
<script src="{{ asset('js/product-review.js') }}"></script>
@endsection