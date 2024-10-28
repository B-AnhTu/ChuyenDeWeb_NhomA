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
                        <button type="submit" class="btn btn-primary" id="search-btn" disabled><i
                                class="fa-solid fa-magnifying-glass"></i></button>
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
                    {{-- lọc sản phẩm theo loại sản phẩm --}}
                    <div class="featured__controls">
                        <ul>
                            @foreach ($categories as $category)
                                <li><a href="#" class="category-filter text-black"
                                        data-id="{{ $category->category_id }}">{{ $category->category_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- sắp xếp theo tên , giá --}}
                    <div class="featured__controls">
                        <ul>
                            <li>Sắp xếp</li>
                            <li><a href="#" class="sort text-black" data-sort="name_asc">Tên A - Z</a></li>
                            <li><a href="#" class="sort text-black" data-sort="name_desc">Tên Z - A</a></li>
                            <li><a href="#" class="sort text-black" data-sort="price_desc">Giá cao đến thấp</a></li>
                            <li><a href="#" class="sort text-black" data-sort="price_asc">Giá thấp đến cao</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($products->isNotEmpty())
                <div class="row featured__filter" id="product-list">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mix fastfood vegetables">
                            <div class="featured__item">
                                <div class="featured__item__pic set-bg"
                                    data-setbg="{{ asset('img/products/' . $product->image) }}">
                                    <ul class="featured__item__pic__hover">
                                        <li><a href="#"><i
                                                    class="fa fa-heart ffa-heart {{ in_array($product->product_id, $likedProductIds) ? 'liked' : '' }}"
                                                    data-id="{{ $product->product_id }}"></i></a></li>
                                        <li> <a href="#"><i class="fa fa-shopping-cart add-to-cart"
                                                    data-id="{{ $product->product_id }}"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="featured__item__text">
                                    <h6><a
                                            href="{{ url('/productDetail/' . $product->slug) }}">{{ $product->product_name }}</a>
                                    </h6>
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
                                <a class="page-link" href="#"
                                    data-page="{{ $i }}">{{ $i }}</a>
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
                        <img src="{{ asset('img/banners/banner1.png') }}" alt="Banner">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="{{ asset('img/banners/banner2.png') }}" alt="Banner">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Blog Section Begin -->
    <section class="from-blog spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title from-blog__title">
                        <h2>Tin tức mới nhất</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @if (isset($posts))
                    @foreach ($posts as $post)
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="blog__item">
                                <div class="blog__item__pic">
                                    <img src="{{ asset('/img/blog/' . $post->image) }}" alt="" class="big-img">
                                </div>
                                <div class="blog__item__text">
                                    <ul>
                                        <li><i class="fa fa-calendar-o"></i>{{ $post->created_at }}</li>
                                        <li><i class="fa fa-comment-o"></i> 0</li>
                                    </ul>
                                    <h5><a
                                            href="{{ route('blog.index', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                                    </h5>
                                    <p>{{ $post->short_description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

    <script>
        var productImageBasePath = "{{ asset('img/products') }}/";
        var isFilterActive = false;
        var currentManufacturerId = null;
        let likedProductIds = @json($likedProductIds);

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
                        <div class="col-lg-3 col-md-4 col-sm-6 mix fastfood vegetables">
                            <div class="featured__item">
                                <div class="featured__item__pic set-bg" style="background-image: url('${productImageBasePath}${product.image}');">
                                    <ul class="featured__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart ffa-heart ${isLiked ? 'liked' : ''}" data-id="${product.product_id}"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart add-to-cart" data-id="${product.product_id}"></i></a></li>
                                    </ul>
                                </div>
                                <div class="featured__item__text">
                                <h6><a href="/productDetail/${product.slug}">${product.product_name}</a></h6>
                                    <h6><a href="#"></a></h6>
                                    <p><i class="fa-solid fa-eye px-1"></i>${product.product_view}</p>
                                    <h5>${numberFormat(product.price)} VNĐ</h5>
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

            // Sắp xếp sản phẩm trên trang hiện tại
            $('.sort').on('click', function(e) {
                e.preventDefault();
                const sortBy = $(this).data('sort');

                // Highlight nút sắp xếp được chọn
                $('.sort').css('color', '');
                $(this).css('color', 'green');

                // Lấy tất cả các sản phẩm trên trang hiện tại
                const productContainer = $('#product-list');
                const products = productContainer.children('.mix').get();

                // Sắp xếp mảng sản phẩm
                products.sort(function(a, b) {
                    return compareProducts(a, b, sortBy);
                });

                // Thêm lại các sản phẩm đã sắp xếp vào container
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
                    url: '/filterByManufacturer',
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
                    url: '/search',
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

            // Filter products by category
            $('.category-filter').on('click', function(e) {
                e.preventDefault();
                $('.category-filter').removeClass('active');
                $(this).addClass('active');
                $(this).css('color', 'green');
                $('.category-filter').not(this).css('color', '');

                isFilterActive = true;
                currentCategoryId = $(this).data('id');
                fetchProductsByCategory(currentCategoryId, 1);

                $('html, body').animate({
                    scrollTop: $("#product-list").offset().top
                }, 500);
            });

            function fetchProductsByCategory(categoryId, page) {
                $.ajax({
                    url: '/filterByCategory',
                    type: 'GET',
                    data: {
                        category_id: categoryId,
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
        });
    </script>
@endsection
