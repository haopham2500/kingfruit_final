<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>King Fruit - Thế Giới Trái Cây Sạch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        :root {
            --king-green: #198754;
            --king-red: #ff3838;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        [data-bs-theme="light"] body {
            background-color: #f4f7f6;
        }

        [data-bs-theme="dark"] body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .navbar {
            background-color: var(--king-green) !important;
            padding: 12px 0;
            z-index: 1050;
            /* Đảm bảo menu luôn nằm trên cùng */
        }

        .nav-link {
            font-weight: 500;
            text-transform: uppercase;
            margin: 0 10px;
            transition: 0.3s;
        }

        .nav-link:hover {
            opacity: 0.8;
        }

        /* Banner Slider */
        .carousel-item {
            height: 500px;
        }

        .carousel-item img {
            object-fit: cover;
            height: 100%;
            filter: brightness(70%);
        }

        .carousel-caption {
            bottom: 30%;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 20px;
            padding: 30px;
        }

        /* Product Card */
        .product-card {
            border-radius: 15px;
            border: none;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        /* Search Bar Focus */
        .navbar input[type="search"] {
            transition: 0.3s;
        }

        .navbar input[type="search"]:focus {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
            width: 250px;
            /* Hiệu ứng giãn nhẹ thanh tìm kiếm khi click */
            outline: none;
        }

        /* Video Footer Section */
        .video-footer-section {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            height: 550px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 50px;
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        .video-content {
            position: relative;
            z-index: 3;
        }

        .main-footer {
            background-color: #1a1b1e;
            color: #adb5bd;
            padding: 60px 0 30px;
        }

        /* Ép toàn bộ khung card phải bằng nhau */
       .product-card {
        transition: transform 0.3s ease;
        overflow: hidden; /* Để bo góc card không bị hình đè lên */
    }

    .product-card:hover {
        transform: translateY(-5px); /* Hiệu ứng bay lên khi rê chuột vào */
    }

    .badge-hot {
        background-color: #ff4757; /* Màu đỏ nổi bật */
        color: white;
        z-index: 10; /* Đảm bảo nó luôn nằm trên ảnh */
        font-size: 0.75rem;
        letter-spacing: 1px;
    }

    .card-img-top {
        height: 250px;
        object-fit: cover; /* Giúp ảnh không bị méo khi kích thước khác nhau */
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">
                <i class="bi bi-apple me-2"></i>KING FRUIT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#kingNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="kingNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('home') }}">{{ __('messages.home') }}</a>
                    </li>

                    <li class="nav-item mx-lg-3 my-2 my-lg-0">
                        <form action="{{ route('search') }}" method="GET" class="d-flex position-relative">
                            <input
                                class="form-control rounded-pill ps-4 pe-5 border-0 shadow-sm"
                                type="search"
                                name="query"
                                value="{{ request('query') }}"
                                placeholder="{{ __('messages.search_placeholder') }}"
                                style="min-width: 220px; height: 38px; font-size: 0.9rem;">
                            <button
                                class="btn position-absolute end-0 top-50 translate-middle-y border-0 text-success"
                                type="submit"
                                style="padding-right: 15px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </li>
                    <li class="nav-item mx-lg-3 my-2 my-lg-0">
                        <form action="{{ route('search') }}" method="GET" class="d-flex position-relative">
                        </form>
                    </li>

                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe fs-5 me-1"></i> {{ strtoupper(session('locale', 'vi')) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'vi') }}">Tiếng Việt</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                        </ul>
                    </li>

                    <li class="nav-item me-3 d-flex align-items-center">
                        <button class="btn btn-link nav-link text-white p-0 border-0" id="theme-toggle" title="Chế độ Sáng/Tối">
                            <i class="bi bi-moon-stars fs-5"></i>
                        </button>
                    </li>

                    <li class="nav-item me-3">
                        <a href="{{ route('cart.index') }}" class="nav-link position-relative d-inline-block d-flex align-items-center">
                            <i class="bi bi-cart3 fs-4 text-white"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; margin-top: 5px;">
                                {{ count(session('cart')) }}
                            </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center"><a href="{{ route('promotions.index') }}" class="nav-link text-white">
    <i class="bi bi-gift me-1"></i> {{ __('messages.promotions') }}
</a></li>

                    

                    <li class="nav-item ms-lg-3 d-flex align-items-center">
                        @guest
                        <a class="btn btn-outline-light rounded-pill px-4 me-2" href="{{ route('login') }}">{{ __('messages.login') }}</a>
                        <a class="btn btn-light text-success rounded-pill px-4" href="{{ route('register') }}">{{ __('messages.register') }}</a>
                        @else
                        <div class="dropdown">
                            <a class="btn btn-light text-success rounded-pill px-4 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(Auth::user()->email == 'admin@gmail.com')
                                <li><a class="dropdown-item" href="{{ route('crud') }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('messages.admin_panel') }}</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @endif
                                <li>
                                    <div class="px-3 py-2 border-bottom">
                                        <div class="fw-semibold text-dark">{{ Auth::user()->name }}</div>
                                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ Auth::user()->phone ?? 'Chưa cập nhật số điện thoại' }}</div>
                                        <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ Auth::user()->email }}</div>
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i> {{ __('messages.profile') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.logout') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        @endguest
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="container text-center">
            <div class="footer-logo">KING FRUIT</div>
            <p>{{ __('messages.address') }}</p>
            <p>{{ __('messages.hotline') }}</p>
            <hr class="my-4 border-secondary">
            <p class="mb-0">{{ __('messages.copyright') }}</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const htmlElement = document.documentElement;
            const themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                const themeIcon = themeToggleBtn.querySelector('i');
                
                // Set current theme from localStorage
                const currentTheme = localStorage.getItem('theme') || 'light';
                htmlElement.setAttribute('data-bs-theme', currentTheme);
                updateIcon(currentTheme);

                // Toggle theme
                themeToggleBtn.addEventListener('click', () => {
                    const newTheme = htmlElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
                    htmlElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateIcon(newTheme);
                });

                function updateIcon(theme) {
                    if (theme === 'dark') {
                        themeIcon.classList.remove('bi-moon-stars');
                        themeIcon.classList.add('bi-sun', 'text-warning');
                    } else {
                        themeIcon.classList.remove('bi-sun', 'text-warning');
                        themeIcon.classList.add('bi-moon-stars');
                    }
                }
            }
        });
    </script>
</body>

</html>