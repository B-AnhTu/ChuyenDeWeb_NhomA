    <!DOCTYPE html>
    <html lang="zxx">

    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Ogani Template">
        <meta name="keywords" content="Ogani, unica, creative, html">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Web bán hàng</title>
        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap"
            rel="stylesheet">

        {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
        <!-- Css Styles -->
        <link rel="stylesheet" href="{{ asset('bootstrap5.3/css/bootstrap.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
        {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}

        {{-- jquery --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- thêm thư viện SweetAlert2 --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>

    <body>
        <!-- Page Preloder -->
        <div id="preloder">
            <div class="loader"></div>
        </div>

        <!-- Humberger Begin -->
        <div class="humberger__menu__overlay"></div>
        <div class="humberger__menu__wrapper">
            <div class="humberger__menu__logo">
                <a href="#"><img src="img/logo.png" alt=""></a>
            </div>
            <div class="humberger__menu__cart">
                <ul>
                    <li><a title="sản phẩm yêu thích" href="{{ url('wishlist') }}"><i class="fa fa-heart"></i></a></li>
                    <li><a title="giỏ hàng" href="{{ url('cart') }}"><i class="fa fa-shopping-bag"></i></a></li>
                </ul>
            </div>
            <div class="humberger__menu__widget">
                <div class="header__top__right__auth">
                    @guest
                        <!-- Hiển thị nút Đăng nhập nếu chưa đăng nhập -->
                        <a href="{{ url('/login') }}"><i class="fa fa-user"></i> Đăng nhập</a>
                    @else
                        <!-- Dropdown khi người dùng đã đăng nhập -->
                        <div class="dropdown-center">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ auth()->user()->fullname }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('Profile-user') }}"><i
                                            class="fa-solid fa-user"></i>Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="{{ url('change-password') }}"><i class="fa fa-lock"></i>
                                        Đổi
                                        mật khẩu</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i
                                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
            <nav class="humberger__menu__nav mobile-menu">
                <ul>
                    <li class="{{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ '/' }}">Trang chủ</a>
                    </li>
                    <li class="{{ request()->is('product*') ? 'active' : '' }}">
                        <a href="{{ url('product') }}">Sản phẩm</a>
                    </li>
                    <li class="{{ request()->is('blog*') ? 'active' : '' }}">
                        <a href="{{ url('/blog') }}">Tin tức</a>
                    </li>
                    <li class="{{ request()->is('contact') ? 'active' : '' }}">
                        <a href="{{ url('/contact') }}">Liên hệ</a>
                    </li>
                </ul>
            </nav>
            <div id="mobile-menu-wrap"></div>
            <div class="header__top__right__social">
                <a href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a>
                <a href="https://x.com/"><i class="fa fa-twitter"></i></a>
                <a href="https://www.linkedin.com/"><i class="fa fa-linkedin"></i></a>
                <a href="https://www.pinterest.com/"><i class="fa fa-pinterest-p"></i></a>
            </div>
            <div class="humberger__menu__contact">
                <ul>
                    <li><i class="fa fa-envelope"></i> webbanhang@gmail.com</li>
                </ul>
            </div>
        </div>
        <!-- Humberger End -->

        <!-- Header Section Begin -->
        <header class="header">
            <div class="header__top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="header__top__left">
                                <ul>
                                    <li><i class="fa fa-envelope"></i> webbanhang@gmail.com</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="header__top__right">
                                <div class="header__top__right__social">
                                    <a href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a>
                                    <a href="https://x.com/"><i class="fa fa-twitter"></i></a>
                                    <a href="https://www.linkedin.com/"><i class="fa fa-linkedin"></i></a>
                                    <a href="https://www.pinterest.com/"><i class="fa fa-pinterest-p"></i></a>
                                </div>
                                <div class="header__top__right__auth">
                                    @guest
                                        <!-- Hiển thị nút Đăng nhập nếu chưa đăng nhập -->
                                        <a href="{{ url('/login') }}"><i class="fa fa-user"></i> Đăng nhập</a>
                                    @else
                                        <!-- Dropdown khi người dùng đã đăng nhập -->
                                        <div class="dropdown-center">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ auth()->user()->fullname }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ url('Profile-user') }}"><i
                                                            class="fa-solid fa-user"></i>Thông tin cá nhân</a></li>
                                                <li>@auth
                                                        <a class="dropdown-item" href="{{ route('orders.my-orders') }}"><i
                                                                class="fa fa-shopping-bag"></i>Đơn hàng của tôi</a>
                                                    @endauth
                                                </li>
                                                <li><a class="dropdown-item" href="{{ route('orders.track-form') }}"><i
                                                            class="fa fa-search"></i>Tra cứu đơn hàng</a></li>
                                                <li><a class="dropdown-item" href="{{ url('change-password') }}"><i
                                                            class="fa fa-lock"></i> Đổi mật khẩu</a></li>
                                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i
                                                            class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                                            </ul>
                                        </div>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hiển thị thông báo lỗi nếu người dùng vai trò user cố đăng nhập vào trang quản trị -->
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="header__logo">
                            <a href="{{ url('/') }}"><img src="img/logo.png" alt=""></a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <nav class="header__menu">
                            <ul>
                                <li class="{{ request()->is('/') ? 'active' : '' }}">
                                    <a href="{{ '/' }}">Trang chủ</a>
                                </li>
                                <li class="{{ request()->is('product*') ? 'active' : '' }}">
                                    <a href="{{ url('product') }}">Sản phẩm</a>
                                </li>
                                <li class="{{ request()->is('blog*') ? 'active' : '' }}">
                                    <a href="{{ url('/blog') }}">Tin tức</a>
                                </li>
                                <li class="{{ request()->is('contact') ? 'active' : '' }}">
                                    <a href="{{ url('/contact') }}">Liên hệ</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-3">
                        <div class="header__cart">
                            <ul>
                                <li><a title="sản phẩm yêu thích" href="{{ url('wishlist') }}"><i
                                            class="fa fa-heart"></i></a></li>
                                <li><a title="giỏ hàng" href="{{ url('cart') }}"><i
                                            class="fa fa-shopping-bag"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="humberger__open">
                    <i class="fa fa-bars"></i>
                </div>
            </div>
        </header>
        <!-- Header Section End -->
        @yield('content')

        <!-- Footer Section Begin -->
        <footer class="footer spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="footer__about">
                            <div class="footer__about__logo">
                                <a href="{{ url('/') }}"><img src="img/logo.png" alt=""></a>
                            </div>
                            <ul>
                                <li>Địa chỉ: 53 Võ Văn Ngân , Phường Linh Chiểu, TP Thủ Đức, TP Hồ Chí Minh</li>
                                <li>Số điện thoại: 0388888888</li>
                                <li>Email: webbanhang@gmail.com</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                        <div class="footer__widget">
                            <h6>Liên kết</h6>
                            <ul>
                                <li><a href="{{ asset('/') }}">Web bán hàng</a></li>
                                <li><a href="{{ asset('/blog') }}">Tin tức</a></li>
                                <li><a href="#">Về chúng tôi</a></li>
                            </ul>
                            <ul>
                                <li><a href="{{ asset('/contact') }}">Liên hệ</a></li>
                                <li><a href="{{ asset('/404') }}">404</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="footer__widget">
                            <h6>Tham gia bản tin của chúng tôi ngay bây giờ</h6>
                            <p>Nhận thông tin cập nhật qua E-mail về cửa hàng mới nhất và các ưu đãi đặc biệt của chúng
                                tôi.
                            </p>
                            <form id="newsletterForm" class="newsletter-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Tên của bạn (không bắt buộc)">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Nhập email của bạn" required>
                                </div>
                            </form>
                            <button type="button" class="site-btn" id="subscribeBtn">
                                <span class="btn-text">Đăng ký</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>

                            <div id="newsletterMessage" class="mt-3"></div>
                            <div class="footer__widget__social mt-4">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer__copyright">
                            <div class="footer__copyright__text">
                                <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                    Copyright &copy;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script> All rights reserved | This template is made with <i
                                        class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com"
                                        target="_blank">Colorlib</a>
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                </p>
                            </div>
                            <div class="footer__copyright__payment"><img src="img/payment-item.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Section End -->
        <script src="https://kit.fontawesome.com/f6dce9b617.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Js Plugins -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
        <!-- <script src="{{ asset('js/send-email.js') }}"></script> -->
        <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/jquery.slicknav.js') }}"></script>
        <script src="{{ asset('js/mixitup.min.js') }}"></script>
        <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script> --}}
        <script src="{{ asset('bootstrap5.3/js/bootstrap.bundle.min.js') }}"></script>






        {{-- Xử lý nút gửi mail --}}
        <script>
            document.getElementById("subscribeBtn").addEventListener("click", function() {
                const form = document.getElementById("newsletterForm");
                const messageDiv = document.getElementById("newsletterMessage");
                const submitBtn = this;
                const btnText = submitBtn.querySelector(".btn-text");
                const spinner = submitBtn.querySelector(".spinner-border");

                // Validate email
                const emailInput = form.querySelector('input[name="email"]');
                if (!emailInput.value) {
                    messageDiv.innerHTML = `
            <div class="alert alert-danger">
                Vui lòng nhập email của bạn.
            </div>`;
                    return;
                }

                // Show loading state
                form.classList.add("loading");
                btnText.textContent = "Đang xử lý...";
                spinner.classList.remove("d-none");

                // Get form data
                const formData = new FormData(form);

                // Create request payload
                const payload = {
                    name: formData.get("name"),
                    email: formData.get("email"),
                    _token: formData.get("_token"), // Include CSRF token
                };

                fetch("/newsletter/subscribe", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Accept: "application/json",
                            "X-CSRF-TOKEN": formData.get("_token"),
                        },
                        body: JSON.stringify(payload),
                    })
                    .then((response) => {
                        if (!response.ok) {
                            return response.json().then((err) => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then((data) => {
                        const alertClass =
                            data.status === "success" ? "alert-success" : "alert-danger";
                        messageDiv.innerHTML = `<div class="alert ${alertClass}">${data.message}</div>`;

                        if (data.status === "success") {
                            form.reset();
                        }
                    })
                    .catch((error) => {
                        let errorMessage = "Có lỗi xảy ra, vui lòng thử lại sau.";
                        if (error.message) {
                            errorMessage = error.message;
                        }
                        messageDiv.innerHTML = `
            <div class="alert alert-danger">
                ${errorMessage}
            </div>`;
                    })
                    .finally(() => {
                        // Reset loading state
                        form.classList.remove("loading");
                        btnText.textContent = "Đăng ký";
                        spinner.classList.add("d-none");

                        // Auto hide message after 5 seconds
                        setTimeout(() => {
                            messageDiv.innerHTML = "";
                        }, 5000);
                    });
            });
        </script>


        {{-- js thêm sản phẩm vào giỏ hàng --}}
        <script>
            // Xử lý sự kiện khi nhấn vào nút "Thêm vào giỏ hàng"
            $(document).on('click', '.add-to-cart', function(e) {
                e.preventDefault();
                let productId = $(this).data('id');

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi. Vui lòng thử lại sau.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        </script>


        {{-- js thích sản phẩm --}}
        <script>
            $(document).on('click', '.ffa-heart', function(e) {
                e.preventDefault();
                let icon = $(this);
                let productId = icon.data('id');

                $.ajax({
                    url: '/product-toggle-like',
                    method: 'POST',
                    data: {
                        product_id: productId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        icon.toggleClass('liked');

                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Vui lòng đăng nhập để thực hiện chức năng này',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else if (xhr.status === 404) {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Sản phẩm không tồn tại',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        </script>
    </body>

    </html>
