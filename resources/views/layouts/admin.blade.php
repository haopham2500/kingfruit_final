<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.admin_panel') }} - King Fruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
            color: #212529;
        }

        [data-bs-theme="dark"] body {
            background-color: #121212;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .main-wrapper,
        [data-bs-theme="dark"] .content,
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .table,
        [data-bs-theme="dark"] .table-responsive,
        [data-bs-theme="dark"] .table thead,
        [data-bs-theme="dark"] .table tbody tr,
        [data-bs-theme="dark"] .table th,
        [data-bs-theme="dark"] .table td,
        [data-bs-theme="dark"] .breadcrumb,
        [data-bs-theme="dark"] .dropdown-menu,
        [data-bs-theme="dark"] .alert,
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select,
        [data-bs-theme="dark"] .badge {
            background-color: #1f1f1f;
            color: #e0e0e0;
            border-color: #343a40;
        }

        [data-bs-theme="dark"] .sidebar {
            background: #0f5132;
        }

        [data-bs-theme="dark"] .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
        }

        [data-bs-theme="dark"] .sidebar .nav-link:hover,
        [data-bs-theme="dark"] .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.12);
            color: white;
        }

        [data-bs-theme="dark"] .btn-light {
            background-color: #2c2c2c !important;
            color: #e9ecef !important;
            border-color: #3e3e3e !important;
        }

        [data-bs-theme="dark"] .btn-outline-secondary,
        [data-bs-theme="dark"] .btn-outline-primary,
        [data-bs-theme="dark"] .btn-outline-success,
        [data-bs-theme="dark"] .btn-outline-warning,
        [data-bs-theme="dark"] .btn-outline-danger {
            color: #e0e0e0;
            border-color: rgba(255, 255, 255, 0.15);
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #252525;
            color: #e0e0e0;
            border-color: #343a40;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(108, 117, 125, 0.25);
        }

        [data-bs-theme="dark"] .text-body,
        [data-bs-theme="dark"] .text-muted,
        [data-bs-theme="dark"] .breadcrumb-item a,
        [data-bs-theme="dark"] .breadcrumb-item.active {
            color: #e0e0e0 !important;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            height: 100vh; /* Cố định chiều cao bằng màn hình để có thể cuộn */
            overflow-y: auto; /* Thêm thanh cuộn dọc khi nội dung vượt quá chiều cao */
            background: #198754;
            color: white;
            position: fixed;
            /* Cố định sidebar khi cuộn trang */
        }

        /* Tùy chỉnh thanh cuộn cho sidebar nhìn mượt hơn */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        [data-bs-theme="dark"] .sidebar {
            background: #0f5132;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 15px 20px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .main-wrapper {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        /* Đẩy nội dung sang phải để không bị sidebar đè */
        .content {
            padding: 30px;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <div class="sidebar shadow">
            <div class="p-4 text-center">
                <h4 class="fw-bold text-uppercase">King Fruit</h4>
                <small class="opacity-75">{{ __('messages.admin_panel') }}</small>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button id="theme-toggle-admin" class="btn btn-sm btn-light text-dark" type="button" title="{{ __('messages.theme_toggle') }}">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light text-dark dropdown-toggle" type="button" id="adminLangDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(session('locale', app()->getLocale())) }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="adminLangDropdown">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'vi') }}">Tiếng Việt</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <nav class="nav flex-column mt-3">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> {{ __('messages.dashboard') }}
                </a>

                <a class="nav-link {{ request()->routeIs('crud') ? 'active' : '' }}" href="{{ route('crud') }}">
                    <i class="bi bi-box-seam me-2"></i> {{ __('messages.manage_products') }}
                </a>

                <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}" href="{{ route('category.index') }}">
                    <i class="bi bi-tags me-2"></i> {{ __('messages.manage_categories') }}
                </a>

                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-2"></i> {{ __('messages.manage_customers') }}
                </a>

                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-truck me-2"></i> {{ __('messages.manage_orders') }}
                </a>

                @php
                    $pendingRefundsCount = \App\Models\Order::where('status', 'wait_refund')->count();
                @endphp
                <a class="nav-link {{ request()->routeIs('admin.refunds.*') ? 'active' : '' }} d-flex justify-content-between align-items-center" href="{{ route('admin.refunds.index') }}">
                    <span>
                        <i class="bi bi-arrow-counterclockwise me-2"></i> {{ __('messages.manage_refunds') }}
                    </span>
                    @if($pendingRefundsCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $pendingRefundsCount }}</span>
                    @endif
                </a>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                        <i class="bi bi-chat-left-text me-2"></i> {{ __('messages.manage_reviews') }}
                    </a>
                </li>

                <a href="{{ route('admin.vouchers.index') }}" class="nav-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated me-2"></i> {{ __('messages.manage_vouchers') }}
                </a>

                <a class="nav-link" href="{{ route('home') }}">
                    <i class="bi bi-house me-2"></i> {{ __('messages.back_to_home') }}
                </a>

                <hr class="mx-3 opacity-25">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.logout') }}
                    </button>
                </form>
            </nav>
        </div>
    </div>
    <div class="main-wrapper">
        <div class="content">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động ẩn thông báo sau 5 giây (5000ms)
        document.addEventListener('DOMContentLoaded', function() {
            // === RESTRICT PHONE NUMBER INPUTS TO DIGITS ONLY ===
            document.addEventListener('input', function(e) {
                if (e.target && e.target.name === 'phone') {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                }
            });

            // === CHỐNG DOUBLE CLICK (SPAM CLICK) GÂY TRÙNG LẶP DỮ LIỆU ===
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (this.dataset.submitted) {
                        e.preventDefault();
                        return;
                    }
                    this.dataset.submitted = 'true';
                    
                    const submitButtons = this.querySelectorAll('button[type="submit"]');
                    submitButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...';
                    });
                });
            });

            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    let bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            const htmlElement = document.documentElement;
            const themeToggleBtn = document.getElementById('theme-toggle-admin');
            if (themeToggleBtn) {
                const themeIcon = themeToggleBtn.querySelector('i');
                const currentTheme = localStorage.getItem('theme') || 'light';
                htmlElement.setAttribute('data-bs-theme', currentTheme);
                updateIcon(currentTheme);

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