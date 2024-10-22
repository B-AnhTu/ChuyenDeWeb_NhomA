@extends('app')
@section('content')

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Tất cả danh mục</span>
                        </div>
                        <ul>
                            @foreach ($manufacturers as $manufacturer)
                                <li><a href="#" class="manufacturer-filter"
                                        data-id="{{ $manufacturer->manufacturer_id }}">{{ $manufacturer->manufacturer_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <form id="search-form" class="d-flex align-items-center pb-5" action="#">
                        <div class="hero__search__categories me-2">
                            <select id="manufacturer-select" style="max-width: 150px;">
                                <option value="">Tất cả danh mục</option>
                                @foreach ($manufacturers->sortByDesc('created_at') as $manufacturer)
                                    <option value="{{ $manufacturer->manufacturer_id }}">
                                        {{ $manufacturer->manufacturer_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" placeholder="Bạn cần gì?" id="search-input" class="form-control me-2 w-50">
                        <button type="submit" class="btn btn-primary" id="search-btn" disabled>Tìm kiếm</button>
                    </form>
                    <div class="hero__item set-bg" data-setbg="img/banners/banner0.gif">
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Featured Section Begin -->
    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Sản phẩm mới nhất</h2>
                    </div>
                    <div class="featured__controls">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            <li data-filter=".oranges">Oranges</li>
                            <li data-filter=".fresh-meat">Fresh Meat</li>
                            <li data-filter=".vegetables">Vegetables</li>
                            <li data-filter=".fastfood">Fastfood</li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($products->isNotEmpty())
                <div class="row featured__filter" id="product-list">
                    @foreach ($products as $product)
                        <div class="col-lg-4 col-md-4 col-sm-6 mix fastfood vegetables">
                            <div class="featured__item">
                                <div class="featured__item__pic set-bg"
                                    data-setbg="{{ asset('img/products/' . $product->image) }}">
                                    <ul class="featured__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="featured__item__text">
                                    <h6><a href="#">{{ $product->product_name }}</a></h6>
                                    <p><i class="fa-solid fa-eye px-1"></i>{{ $product->product_view }}</p>
                                    <h5>{{ number_format($product->price) }} vnđ</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Phân trang -->
                <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-4" id="pagination">
                    <ul class="pagination">
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="#" data-page="{{ $products->currentPage() - 1 }}">Previous</a>
                        </li>
                        @for ($i = 1; $i <= $products->lastPage(); $i++)
                            <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="#" data-page="{{ $products->currentPage() + 1 }}">Next</a>
                        </li>
                    </ul>
                </nav>
            @else
                <p>Không có sản phẩm nào để hiển thị.</p>
            @endif
        </div>
    </section>
    <!-- Featured Section End -->

    <!-- Banner Begin -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="img/banner/banner-1.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="img/banner/banner-2.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Latest Product Section Begin -->
    <section class="latest-product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="latest-product__text">
                        <h4>Latest Products</h4>
                        <div class="latest-product__slider owl-carousel">
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="img/latest-product/lp-3.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Crab Pool Security</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                            </div>
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="img/latest-product/lp-3.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Crab Pool Security</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="latest-product__text">
                        <h4>Top Rated Products</h4>
                        <div class="latest-product__slider owl-carousel">
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="img/latest-product/lp-3.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Crab Pool Security</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                            </div>
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="img/latest-product/lp-3.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Crab Pool Security</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="latest-product__text">
                        <h4>Review Products</h4>
                        <div class="latest-product__slider owl-carousel">
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="img/latest-product/lp-3.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Crab Pool Security</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                            </div>
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="img/latest-product/lp-3.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Crab Pool Security</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Latest Product Section End -->

    <!-- Blog Section Begin -->
    <section class="from-blog spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title from-blog__title">
                        <h2>From The Blog</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="img/blog/blog-1.jpg" alt="">
                        </div>
                        <div class="blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> May 4,2019</li>
                                <li><i class="fa fa-comment-o"></i> 5</li>
                            </ul>
                            <h5><a href="#">Cooking tips make cooking simple</a></h5>
                            <p>Sed quia non numquam modi tempora indunt ut labore et dolore magnam aliquam quaerat </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="img/blog/blog-2.jpg" alt="">
                        </div>
                        <div class="blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> May 4,2019</li>
                                <li><i class="fa fa-comment-o"></i> 5</li>
                            </ul>
                            <h5><a href="#">6 ways to prepare breakfast for 30</a></h5>
                            <p>Sed quia non numquam modi tempora indunt ut labore et dolore magnam aliquam quaerat </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="img/blog/blog-3.jpg" alt="">
                        </div>
                        <div class="blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> May 4,2019</li>
                                <li><i class="fa fa-comment-o"></i> 5</li>
                            </ul>
                            <h5><a href="#">Visit the clean farm in the US</a></h5>
                            <p>Sed quia non numquam modi tempora indunt ut labore et dolore magnam aliquam quaerat </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- js hiển thị sản phẩm mới nhất và sản phẩm thuộc nhà sản xuất và phân trang mà không tải lại trang --}}
    <script>
        var productImageBasePath = "{{ asset('img/products') }}/";
        var isFilterActive = false;
        var currentManufacturerId = null;
    
        $(document).ready(function() {
            // Hàm cập nhật danh sách sản phẩm
            function updateProductList(response) {
                $('#product-list').html('');
                if (response.data.length === 0) {
                    $('#product-list').append('<p>Không tìm thấy sản phẩm nào.</p>');
                    return;
                }
    
                response.data.forEach(function(product) {
                    $('#product-list').append(`
                        <div class="col-lg-4 col-md-4 col-sm-6 mix fastfood vegetables">
                            <div class="featured__item">
                                <div class="featured__item__pic set-bg" style="background-image: url('${productImageBasePath}${product.image}');">
                                    <ul class="featured__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="featured__item__text">
                                    <h6><a href="#">${product.product_name}</a></h6>
                                    <p><i class="fa-solid fa-eye px-1"></i>${product.product_view}</p>
                                    <h5>${numberFormat(product.price)} VNĐ</h5>
                                </div>
                            </div>
                        </div>
                    `);
                });
                updatePagination(response.current_page, response.last_page);
                if (typeof setBackgrounds === 'function') {
                    setBackgrounds();
                }
            }
    
            // Pagination for products (newest or filtered)
            $(document).on('click', '#pagination .page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                if (isFilterActive) {
                    fetchProductsByManufacturer(currentManufacturerId, page);
                } else {
                    fetchNewestProducts(page);
                }
            });
    
            // Filter products by manufacturer
            $('.manufacturer-filter').on('click', function(e) {
                e.preventDefault();
                $('.manufacturer-filter').removeClass('active');
                $(this).addClass('active');
                $(this).css('color', 'green');
                $('.manufacturer-filter').not(this).css('color', '');
    
                isFilterActive = true;
                currentManufacturerId = $(this).data('id');
                fetchProductsByManufacturer(currentManufacturerId, 1);
    
                $('html, body').animate({
                    scrollTop: $("#product-list").offset().top
                }, 500);
            });
    
            function fetchNewestProducts(page) {
                $.ajax({
                    url: '{{ route('products.index') }}',
                    type: 'GET',
                    data: {
                        page: page
                    },
                    success: function(response) {
                        updateProductList(response);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            }
    
            function fetchProductsByManufacturer(manufacturerId, page) {
                $.ajax({
                    url: '/filter',
                    type: 'GET',
                    data: {
                        manufacturer_id: manufacturerId,
                        page: page
                    },
                    success: function(response) {
                        updateProductList(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
    
            function updatePagination(currentPage, lastPage) {
                var pagination = '';
                pagination += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                </li>`;
    
                for (var i = 1; i <= lastPage; i++) {
                    pagination += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
                }
    
                pagination += `<li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                </li>`;
    
                $('#pagination .pagination').html(pagination);
            }
    
            function numberFormat(number) {
                return new Intl.NumberFormat('vi-VN').format(number);
            }
    
            // Tìm kiếm theo danh mục
            $('#manufacturer-select, #search-input').on('input change', function() {
                const selectedManufacturer = $('#manufacturer-select').val();
                const searchInput = $('#search-input').val().trim();
                $('#search-btn').prop('disabled', !(selectedManufacturer || searchInput));
            });
    
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                const manufacturerId = $('#manufacturer-select').val();
                const keyword = $('#search-input').val().trim();
    
                if (manufacturerId || keyword) {
                    searchProducts(manufacturerId, keyword);
                }
            });
    
            function searchProducts(manufacturerId, keyword) {
                $.ajax({
                    url: '/search', // Đường dẫn tới route tìm kiếm
                    type: 'GET',
                    data: {
                        manufacturer_id: manufacturerId,
                        keyword: keyword
                    },
                    success: function(response) {
                        updateProductList(response);
                        $('html, body').animate({
                            scrollTop: $("#product-list").offset().top
                        }, 500);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            }
        });
    </script>
    

@endsection
