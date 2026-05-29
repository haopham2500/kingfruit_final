<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ __('messages.site_title') }}</title>
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
            color: #ffffff;
        }

        .carousel-caption h1,
        .carousel-caption p {
            color: #ffffff;
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
        z-index: 5; /* Đảm bảo proper stacking */
        font-size: 0.75rem;
        letter-spacing: 1px;
    }

    .card-img-top {
        height: 250px;
        object-fit: cover; /* Giúp ảnh không bị méo khi kích thước khác nhau */
    }

    /* Promotion Popup Modal Styles */
    .modal-promotion {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        animation: fadeIn 0.3s ease-in;
    }

    .modal-promotion.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-promotion-content {
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        max-width: 600px;
        width: 90%;
        position: relative;
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
    }

    .modal-promotion-close {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2001;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 24px;
        transition: all 0.2s;
    }

    .modal-promotion-close:hover {
        background: #dc3545;
        color: white;
        transform: rotate(90deg);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Promotion Banner Styles */
    .promotion-banner {
        background: linear-gradient(135deg, #ff3838 0%, #dc143c 50%, #c41e3a 100%);
        position: relative;
        padding: 30px 20px;
        text-align: center;
        overflow: hidden;
        border-radius: 15px 15px 0 0;
    }

    .promotion-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .promotion-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .special-offer-text {
        color: white;
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 3px;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .big-sale-text {
        color: #FFD700;
        font-size: 48px;
        font-weight: 900;
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
        margin: 10px 0;
        position: relative;
        z-index: 1;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .sale-percentage {
        color: white;
        font-size: 36px;
        font-weight: bold;
        position: absolute;
        right: 30px;
        top: 60px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .promotion-content {
        padding: 30px 20px;
        text-align: center;
    }

    .promotion-logo {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .promotion-logo i {
        font-size: 60px;
        color: #198754;
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
                        <form action="{{ Route::has('search') ? route('search') : url('/search') }}" method="GET" class="d-flex position-relative">
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

                    <li class="nav-item d-flex align-items-center me-2">
                        <button id="theme-toggle" class="btn btn-outline-light rounded-pill px-3" type="button" title="{{ __('messages.theme_toggle') }}">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>

                    <li class="nav-item d-flex align-items-center me-2">
                        <div class="btn-group" role="group" aria-label="Chọn ngôn ngữ">
                            <a class="btn btn-outline-light btn-sm rounded-start-pill {{ session('locale', 'vi') === 'vi' ? 'active' : '' }}" href="{{ route('lang.switch', 'vi') }}">VI</a>
                            <a class="btn btn-outline-light btn-sm rounded-end-pill {{ session('locale', 'vi') === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">EN</a>
                        </div>
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
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); showPromotionModal();">
                                        <i class="bi bi-gift-fill me-2" style="color: #ff3838;"></i>{{ __('messages.promotion_popup_preview') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i> {{ __('messages.profile_title') }}
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

    <!-- Promotion Popup Modal -->
    <div id="promotionModal" class="modal-promotion">
        <div class="modal-promotion-content">
            <button type="button" class="modal-promotion-close" id="closePromotionModal" title="Đóng">
                <i class="bi bi-x"></i>
            </button>
            <a href="{{ route('promotions.index') }}" style="text-decoration: none; color: inherit;">
                <!-- Banner với Big Sale -->
                <div class="promotion-banner">
                    <div class="special-offer-text">{{ __('messages.special_offer') }}</div>
                    <div class="big-sale-text">{{ __('messages.big_sale') }}</div>
                    <div class="sale-percentage">50%<br>OFF</div>
                </div>
                
                <!-- Content -->
                <div class="promotion-content">
                    <div class="promotion-logo">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <h2 style="color: #333; font-weight: bold; margin-bottom: 10px; font-size: 22px;">{{ __('messages.promotion_popup_title') }}</h2>
                    <p style="color: #666; font-size: 14px; margin-bottom: 15px; font-weight: 500;">
                        {{ __('messages.promotion_popup_desc') }}
                    </p>
                    <button type="button" class="btn btn-success btn-lg" style="width: 100%; border-radius: 50px; font-weight: bold; padding: 12px;">
                        <i class="bi bi-tag-fill me-2"></i>{{ __('messages.promotion_popup_cta') }}
                    </button>
                </div>
            </a>
        </div>
    </div>

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
        // Function to show promotion modal (can be called multiple times)
        function showPromotionModal() {
            const promotionModal = document.getElementById('promotionModal');
            promotionModal.classList.add('show');
        }

        document.addEventListener('DOMContentLoaded', () => {
            // === RESTRICT PHONE NUMBER INPUTS TO DIGITS ONLY ===
            document.addEventListener('input', function(e) {
                if (e.target && e.target.name === 'phone') {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                }
            });

            // === PROMOTION POPUP MODAL ===
            const promotionModal = document.getElementById('promotionModal');
            const closeBtn = document.getElementById('closePromotionModal');

            // Close modal when X button is clicked
            closeBtn.addEventListener('click', () => {
                promotionModal.classList.remove('show');
            });

            // Close modal when clicking outside the content
            promotionModal.addEventListener('click', (e) => {
                if (e.target === promotionModal) {
                    promotionModal.classList.remove('show');
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && promotionModal.classList.contains('show')) {
                    promotionModal.classList.remove('show');
                }
            });

            // === THEME TOGGLE IN ACCOUNT DROPDOWN ===
            const htmlElement = document.documentElement;
            const themeToggleInMenu = document.getElementById('toggleThemeDropdown');
            const lightModeLabel = @json(__('messages.light_mode'));
            const darkModeLabel = @json(__('messages.dark_mode'));
            
            if (themeToggleInMenu) {
                const updateThemeUI = (theme) => {
                    const icon = document.getElementById('themeIconDropdown');
                    const text = document.getElementById('themeTextDropdown');
                    if (theme === 'dark') {
                        icon.classList.remove('bi-moon-stars');
                        icon.classList.add('bi-sun', 'text-warning');
                        text.textContent = lightModeLabel;
                    } else {
                        icon.classList.remove('bi-sun', 'text-warning');
                        icon.classList.add('bi-moon-stars');
                        text.textContent = darkModeLabel;
                    }
                };

                // Set initial theme
                const currentTheme = localStorage.getItem('theme') || 'light';
                htmlElement.setAttribute('data-bs-theme', currentTheme);
                updateThemeUI(currentTheme);

                // Toggle theme
                themeToggleInMenu.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newTheme = htmlElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
                    htmlElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeUI(newTheme);
                });
            }

            // === OLD THEME TOGGLE SUPPORT (if exists in navbar) ===
            const oldThemeToggleBtn = document.getElementById('theme-toggle');
            if (oldThemeToggleBtn) {
                const updateOldIcon = (theme) => {
                    const icon = oldThemeToggleBtn.querySelector('i');
                    if (theme === 'dark') {
                        icon.classList.remove('bi-moon-stars');
                        icon.classList.add('bi-sun', 'text-warning');
                    } else {
                        icon.classList.remove('bi-sun', 'text-warning');
                        icon.classList.add('bi-moon-stars');
                    }
                };

                const currentTheme = localStorage.getItem('theme') || 'light';
                htmlElement.setAttribute('data-bs-theme', currentTheme);
                updateOldIcon(currentTheme);

                oldThemeToggleBtn.addEventListener('click', () => {
                    const newTheme = htmlElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
                    htmlElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateOldIcon(newTheme);
                });
            }
        });
    </script>
</body>

</html>