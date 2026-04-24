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

        /* Product Card - Giống vuatraicay.click */
        .product-card {
            border-radius: 15px;
            border: none;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            padding: 15px;
            border-radius: 25px;
            height: 250px;
            object-fit: cover;
        }

        .badge-hot {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--king-red);
        }

        .badge-cate {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--king-green);
            font-size: 10px;
        }

        .price-text {
            color: var(--king-red);
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn-buy {
            background-color: var(--king-red);
            border: none;
            border-radius: 50px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-buy:hover {
            background-color: #d63031;
            transform: scale(1.05);
        }

        /* Section Title */
        .section-header {
            border-bottom: 2px solid #eee;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .section-header h2 {
            font-weight: 800;
            color: #333;
            position: relative;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 80px;
            height: 3px;
            background: var(--king-green);
        }

        /* Footer */
        .main-footer {
            background-color: #1a1b1e;
            color: #adb5bd;
            padding: 60px 0 30px;
        }

        .footer-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 20px;
        }

        /* Đảm bảo phần section chiếm trọn chiều ngang */
        .video-footer-section {
            position: relative;
            width: 100vw;
            /* Chiều ngang bằng 100% màn hình */
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
            /* Tạo khoảng cách với phần trên */
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
            background: rgba(0, 0, 0, 0.4);
            /* Làm tối nền để nổi chữ */
            z-index: 2;
        }

        .video-content {
            position: relative;
            z-index: 3;
        }

        .navbar input[type="search"]:focus {
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            outline: none;
        }
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

                    <li class="nav-item mx-lg-3 my-2 my-lg-0">
                        <form action="{{ route('search') }}" method="GET" class="d-flex position-relative">
                            <input
                                class="form-control rounded-pill ps-4 pe-5 border-0 shadow-sm"
                                type="search"
                                name="query"
                                placeholder="Tìm trái cây..."
                                style="min-width: 220px; height: 38px; font-size: 0.9rem;">
                            <button
                                class="btn position-absolute end-0 top-50 translate-middle-y border-0 text-success"
                                type="submit"
                                style="padding-right: 15px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </li>
                    <li class="nav-item"><a class="nav-link text-warning" href="#"><i class="bi bi-gift me-1"></i> Khuyến mãi</a></li>
                    <li class="nav-item ms-lg-3 d-flex align-items-center">
                        @guest
                        {{-- Hiển thị khi chưa đăng nhập --}}
                        <a class="btn btn-outline-light rounded-pill px-4 me-2" href="{{ route('login') }}">Đăng nhập</a>
                        <a class="btn btn-light text-success rounded-pill px-4" href="{{ route('register') }}">Đăng ký</a>
                        @else
                        {{-- Hiển thị khi đã đăng nhập --}}
                        <div class="dropdown">
                            <a class="btn btn-light text-success rounded-pill px-4 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(Auth::user()->email == 'admin@kingfruit.com')
                                <li><a class="dropdown-item" href="{{ route('crud') }}"><i class="bi bi-speedometer2 me-2"></i>Quản trị hệ thống</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @endif
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
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
            <p>Địa chỉ: 53 Đ. Võ Văn Ngân, Thủ Đức, TP. HCM</p>
            <p>Hotline: 0911 90 90 90</p>
            <hr class="my-4 border-secondary">
            <p class="mb-0">© 2026 King Fruit - Dự án Môn Back End 2</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>