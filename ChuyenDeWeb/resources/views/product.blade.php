@extends('app')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('img/banners/blackFriday.gif') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Organi Shop</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ url('/') }}">Trang chủ</a>
                            <span>Sản phẩm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Tất cả danh mục</h4>
                            <ul>
                                @foreach ($manufacturers as $manufacturer)
                                    <li><a href="#"
                                            class="manufacturer-filter"data-id="{{ $manufacturer->manufacturer_id }}">{{ $manufacturer->manufacturer_name }}</a>
                                @endforeach
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Sản phẩm khác</h4>
                                @if ($products->isNotEmpty())
                                    <div class="latest-product__slider owl-carousel">
                                        @foreach ($products as $product)
                                            <div class="latest-prdouct__slider__item">
                                                <a href="{{ url('/productDetail/' . $product->slug) }}"
                                                    class="latest-product__item">
                                                    <div class="latest-product__item__pic">
                                                        <img src="{{ asset('img/products/' . $product->image) }}"
                                                            alt="$product->image">
                                                    </div>
                                                    <div class="latest-product__item__text">
                                                        <h6>{{ $product->product_name }}
                                                        </h6>
                                                        <span>{{ number_format($product->price) }} VNĐ</span>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p>Không có sản phẩm nào để hiển thị.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="product__discount">
                        {{-- form search --}}
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
                            <input type="text" placeholder="Bạn cần gì?" id="search-input"
                                class="form-control me-2 w-50">
                            <button type="submit" class="btn btn-primary" id="search-btn" disabled><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                        {{-- end form search --}}
                        <div class="section-title product__discount__title">
                            <h2>Sản phẩm mới nhất</h2>
                        </div>
                        <div class="row">
                            @if ($products->isNotEmpty())
                                <div class="product__discount__slider owl-carousel">
                                    @foreach ($products as $product)
                                        <div class="col-lg-4">
                                            <div class="product__discount__item">
                                                <div class="product__discount__item__pic set-bg"
                                                    data-setbg="{{ asset('img/products/' . $product->image) }}">
                                                    <ul class="product__item__pic__hover">
                                                        <li><a href="#"><i
                                                                    class="fa fa-heart ffa-heart {{ in_array($product->product_id, $likedProductIds) ? 'liked' : '' }}"
                                                                    data-id="{{ $product->product_id }}"></i></a></li>
                                                        <li> <a href="#"><i class="fa fa-shopping-cart add-to-cart"
                                                                    data-id="{{ $product->product_id }}"></i></a>
                                                    </ul>
                                                </div>
                                                <div class="product__discount__item__text">
                                                    <span>{{ $product->manufacturer->manufacturer_name }}</span>
                                                    <h5><a
                                                            href="{{ url('/productDetail/' . $product->slug) }}">{{ $product->product_name }}</a>
                                                    </h5>
                                                    <p><i class="fa-solid fa-eye px-1"></i>{{ $product->product_view }}</p>
                                                    <div class="product__item__price">{{ number_format($product->price) }}
                                                        VNĐ</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Không có sản phẩm nào để hiển thị.</p>
                            @endif
                        </div>
                    </div>
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sắp xếp theo</span>
                                    <select>
                                        <option class="sort text-black" value="name_asc">Tên A đến Z</option>
                                        <option class="sort text-black" value="name_desc">Tên Z đến A</option>
                                        <option class="sort text-black" value="price_desc">Giá cao đến thấp</option>
                                        <option class="sort text-black" value="price_asc">Giá thấp đến cao</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($products->isNotEmpty())
                        <div class="row" id="product-list">
                            @foreach ($products as $product)
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg"
                                            data-setbg="{{ asset('img/products/' . $product->image) }}">
                                            <ul class="product__item__pic__hover">
                                                <li><a href="#"><i
                                                            class="fa fa-heart ffa-heart {{ in_array($product->product_id, $likedProductIds) ? 'liked' : '' }}"
                                                            data-id="{{ $product->product_id }}"></i></a></li>
                                                <li> <a href="#"><i class="fa fa-shopping-cart add-to-cart"
                                                            data-id="{{ $product->product_id }}"></i></a>
                                            </ul>
                                        </div>
                                        <div class="product__item__text">
                                            <h6><a
                                                    href="{{ url('/productDetail/' . $product->slug) }}">{{ $product->product_name }}</a>
                                            </h6>
                                            <p><i class="fa-solid fa-eye px-1"></i>{{ $product->product_view }}</p>
                                            <h5>{{ number_format($product->price) }} VNĐ</h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Không có sản phẩm nào để hiển thị.</p>
                    @endif
                    <!-- Phân trang -->
                    <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-4" id="pagination">
                        <ul class="pagination">
                            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="#"
                                    data-page="{{ $products->currentPage() - 1 }}">Previous</a>
                            </li>
                            @for ($i = 1; $i <= $products->lastPage(); $i++)
                                <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="#"
                                        data-page="{{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="#"
                                    data-page="{{ $products->currentPage() + 1 }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
    <script>
        var productImageBasePath = "{{ asset('img/products') }}/";
        var isFilterActive = false;
        var currentManufacturerId = null;
        let likedProductIds = @json($likedProductIds);
        let searchState = {
            manufacturerId: '',
            keyword: ''
        };

        $(document).ready(function() {
            // Hàm so sánh để sắp xếp sản phẩm
            function compareProducts(a, b, sortBy) {
                switch (sortBy) {
                    case 'name_asc':
                        return $(a).find('h6 a').text().localeCompare($(b).find('h6 a').text());
                    case 'name_desc':
                        return $(b).find('h6 a').text().localeCompare($(a).find('h6 a').text());
                    case 'price_asc':
                        return parseFloat($(a).find('h5').text().replace(/[^\d]/g, '')) -
                            parseFloat($(b).find('h5').text().replace(/[^\d]/g, ''));
                    case 'price_desc':
                        return parseFloat($(b).find('h5').text().replace(/[^\d]/g, '')) -
                            parseFloat($(a).find('h5').text().replace(/[^\d]/g, ''));
                    default:
                        return 0;
                }
            }

            // Hàm cập nhật danh sách sản phẩm
            function updateProductList(response) {
                $('#product-list').html('');
                if (response.data.length === 0) {
                    $('#product-list').append('<p>Không tìm thấy sản phẩm nào.</p>');
                    return;
                }

                response.data.forEach(function(product) {
                    let isLiked = likedProductIds.includes(product.product_id);
                    $('#product-list').append(`
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg"
                                            style="background-image: url('${productImageBasePath}${product.image}');">
                                        <ul class="product__item__pic__hover">
                                                <li><a href="#"><i class="fa fa-heart ffa-heart ${isLiked ? 'liked' : ''}" data-id="${product.product_id}"></i></a></li>
                                            <li>
                                                <a href="#"><i class="fa fa-shopping-cart add-to-cart" data-id="${product.product_id}"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                        <div class="product__item__text">
                                            <h6>
                                                <a href="/productDetail/${product.slug}">${product.product_name}</a>
                                            </h6>
                                            <p><i class="fa-solid fa-eye px-1"></i>${product.product_view}</p>
                                            <h5>${numberFormat(product.price)} VNĐ</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });

                // Sau khi cập nhật danh sách, áp dụng lại sắp xếp nếu có
                const activeSort = $('.sort[style*="color: green"]');
                if (activeSort.length) {
                    const sortBy = activeSort.data('sort');
                    const products = $('#product-list').children('.mix').get();
                    products.sort(function(a, b) {
                        return compareProducts(a, b, sortBy);
                    });
                    $.each(products, function(index, item) {
                        $('#product-list').append(item);
                    });
                }

                updatePagination(response.current_page, response.last_page);
                if (typeof setBackgrounds === 'function') {
                    setBackgrounds();
                }
            }

            // Pagination for products (newest or filtered)
            $(document).on('click', '#pagination .page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                if(searchState.keyword || searchState.manufacturerId) {
                    searchProducts(searchState.manufacturerId, searchState.keyword, page);
                }
                else if (isFilterActive) {
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

            // Sắp xếp sản phẩm trên trang hiện tại
            $('.filter__sort select').on('change', function() {
                const sortBy = $(this).val();
                const productContainer = $('#product-list');
                const products = productContainer.children('.col-lg-4').get();

                products.sort(function(a, b) {
                    return compareProducts(a, b, sortBy);
                });

                productContainer.empty();
                $.each(products, function(index, item) {
                    productContainer.append(item);
                });

                // Scroll đến vị trí danh sách sản phẩm
                $('html, body').animate({
                    scrollTop: $("#product-list").offset().top
                }, 500);
            });

            function fetchNewestProducts(page) {
                $.ajax({
                    url: '{{ route('products.product') }}',
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
                    url: '/filterByManufacturers',
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
                    searchProducts(manufacturerId, keyword, 1);
                }
            });

            function searchProducts(manufacturerId, keyword, page = 1) {
                // Lưu trạng thái tìm kiếm hiện tại
                searchState.manufacturerId = manufacturerId;
                searchState.keyword = keyword;
                $.ajax({
                    url: '/searchProduct',
                    type: 'GET',
                    data: {
                        manufacturer_id: manufacturerId,
                        keyword: keyword,
                        page: page
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
