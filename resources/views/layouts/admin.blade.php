<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - King Fruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; overflow-x: hidden; }
        .sidebar { 
            min-width: 250px; 
            max-width: 250px; 
            min-height: 100vh; 
            background: #198754; 
            color: white; 
            position: fixed; /* Cố định sidebar khi cuộn trang */
        }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 15px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.1); color: white; }
        .main-wrapper { margin-left: 250px; width: 100%; } /* Đẩy nội dung sang phải để không bị sidebar đè */
        .content { padding: 30px; }
    </style>
</head>
<body>
    <div class="d-flex">
    <div class="sidebar shadow">
        <div class="p-4 text-center">
            <h4 class="fw-bold text-uppercase">King Fruit</h4>
            <small class="opacity-75">Hệ thống quản trị</small>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link {{ request()->routeIs('crud') ? 'active' : '' }}" href="{{ route('crud') }}">
                <i class="bi bi-box-seam me-2"></i> Quản lý sản phẩm
            </a>
            
            <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}" href="{{ route('category.index') }}">
                <i class="bi bi-tags me-2"></i> Quản lý danh mục
            </a>

            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people me-2"></i> Quản lý khách hàng
            </a>

            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-truck me-2"></i> Quản lý đơn hàng
            </a>

            <a href="{{ route('admin.vouchers.index') }}" class="nav-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated me-2"></i> Quản lý Voucher
            </a>

            <a class="nav-link" href="{{ route('home') }}">
                <i class="bi bi-house me-2"></i> Quay lại trang chủ
            </a>
            
            <hr class="mx-3 opacity-25">
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                </button>
            </form>
        </nav>
    </div>
</div>
        <div class="main-wrapper">
            <div class="content">
                @yield('content') </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>