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
            background-color: #f4f7f6;
        }

        .navbar {
            background-color: var(--king-green) !important;
            padding: 15px 0;
        }

        .nav-link {
            font-weight: 500;
            text-transform: uppercase;
            margin: 0 10px;
        }

        /* Hiệu ứng rung nhẹ cho icon giỏ hàng khi có hàng */
        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
            transition: transform 0.2s;
        }

        .cart-icon-wrapper:hover {
            transform: scale(1.1);
        }

        .badge-cart {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: var(--king-red);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border: 2px solid var(--king-green);
        }

        /* ... Giữ nguyên các Style cũ của bạn ... */
        .carousel-item { height: 500px; }
        .carousel-item img { object-fit: cover; height: 100%; filter: brightness(70%); }
        .product-card { border-radius: 15px; border: none; transition: 0.3s; position: relative; overflow: hidden; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); }
        .price-text { color: var(--king-red); font-size: 1.2rem; font-weight: bold; }
        .main-footer { background-color: #1a1b1e; color: #adb5bd; padding: 60px 0 30px; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="/">
                <i class="bi bi-apple me-2"></i>KING FRUIT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#kingNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="kingNav">
                <ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('home') }}">Trang chủ</a>
    </li>

    <li class="nav-item mx-lg-2">
        <form action="{{ route('search') }}" method="GET" class="d-flex position-relative">
            <input class="form-control rounded-pill ps-3 pe-5 border-0 shadow-sm" 
                   type="search" name="query" placeholder="Tìm trái cây..." 
                   style="width: 200px; height: 35px; font-size: 0.85rem;">
            <button class="btn position-absolute end-0 top-50 translate-middle-y border-0 text-success" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </li>

    <li class="nav-item px-2">
        <a href="{{ route('cart.index') }}" class="cart-icon-wrapper text-decoration-none">
            <i class="bi bi-cart3 fs-4 text-white"></i>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="badge-cart">
                    {{ count(session('cart')) }}
                </span>
            @endif
        </a>
    </li>

    <li class="nav-item"><a class="nav-link text-warning small" href="#"><i class="bi bi-gift"></i> Khuyến mãi</a></li>
    
    <li class="nav-item ms-lg-2">
        @guest
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-light rounded-pill px-3" href="{{ route('login') }}">Đăng nhập</a>
                <a class="btn btn-sm btn-light text-success rounded-pill px-3" href="{{ route('register') }}">Đăng ký</a>
            </div>
        @else
            <div class="dropdown">
                <a class="btn btn-sm btn-light text-success rounded-pill px-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    @if(Auth::user()->role == 'admin')
                        <li><a class="dropdown-item" href="{{ route('crud') }}"><i class="bi bi-speedometer2"></i> Quản trị</a></li>
                        <li><hr class="dropdown-divider"></li>
                    @endif
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        @endguest
    </li>
</ul>
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
            <p>Địa chỉ: 53 Đ. Võ Văn Ngân, Thủ Đức, TP. HCM</p>
            <p>Hotline: 0911 90 90 90</p>
            <hr class="my-4 border-secondary">
            <p class="mb-0">© 2026 King Fruit - Dự án Môn Back End 2</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>