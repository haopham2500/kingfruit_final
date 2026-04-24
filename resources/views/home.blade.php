@extends('layouts.master')

@section('content')
<style>
    /* 1. Ép tất cả ảnh sản phẩm về tỉ lệ vuông 1:1 */
    .product-card .card-img-top {
        aspect-ratio: 1 / 1;
        object-fit: cover;
        width: 100%;
        background-color: #fff;
    }

    /* 2. Đảm bảo Card luôn có chiều cao tối thiểu bằng nhau */
    .product-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    /* 3. Căn đều nội dung bên trong Card */
    .product-card .card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        padding: 1.25rem 1rem;
    }

    /* 4. Ép tên sản phẩm hiển thị tối đa 2 dòng để không làm lệch khung */
    .card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 3rem; 
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    /* 5. Đẩy phần giá và nút bấm luôn nằm sát đáy card */
    .price-text {
        margin-top: auto; 
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>

@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100" alt="Banner 1" style="height: 500px; object-fit: cover; filter: brightness(80%);">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="display-3 fw-bold text-uppercase">Mùa Thu Tươi Mát</h1>
                <p class="lead">Trái cây tươi ngon, bổ dưỡng từ thiên nhiên</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="section-header d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold"><i class="bi bi-fire text-danger me-2"></i>SẢN PHẨM BÁN CHẠY</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-2">
        @foreach($bestSellers as $best)
        <div class="col">
            <div class="card h-100 product-card shadow-sm border-0 position-relative">
                <span class="badge rounded-pill p-2" style="background: linear-gradient(45deg, #ff416c, #ff4b2b); position: absolute; top: 10px; left: 10px; color: white; z-index: 10;">
                    <i class="bi bi-star-fill"></i> HOT
                </span>
                
                <img src="{{ $best->image ? asset('images/' . $best->image) : 'https://placehold.co/400x400?text=' . $best->name }}" class="card-img-top" alt="{{ $best->name }}">
                
                <div class="card-body text-center">
                    <p class="text-success small mb-1 fw-bold text-uppercase">{{ $best->category_name ?? 'Nội địa' }}</p>
                    <h5 class="card-title fw-bold">{{ $best->name }}</h5>
                    <div class="price-text mb-3 text-danger fw-bold fs-5">{{ number_format($best->price) }} đ</div>
                    
                    <form action="{{ route('cart.add', $best->id) }}" method="POST" class="mt-auto">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 btn-sm rounded-pill shadow-sm py-2">
                            <i class="bi bi-cart-plus me-1"></i> Mua ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="section-header mt-5 mb-4">
        <h2 class="fw-bold text-success"><i class="bi bi-grid-fill me-2"></i>TẤT CẢ SẢN PHẨM</h2>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mt-2">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 product-card shadow-sm border-0 position-relative">
                <span class="badge rounded-pill px-3 bg-success text-white" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                    {{ $product->category_name ?? 'Trái cây' }}
                </span>

                <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://placehold.co/400x400?text=' . $product->name }}" class="card-img-top" alt="{{ $product->name }}">
                
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                    <div class="price-text mb-3 text-danger fw-bold fs-5">{{ number_format($product->price) }} VNĐ</div>
                    
                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-success btn-sm rounded-pill flex-fill py-2">Chi tiết</a>
                        
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-fill m-0">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 btn-sm rounded-pill shadow-sm py-2">
                                Mua ngay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div> 

<div class="video-footer-section position-relative w-100 mt-5" style="height: 400px; overflow: hidden;">
    <video autoplay muted loop playsinline class="w-100 h-100" style="object-fit: cover;">
        <source src="{{ asset('videos/bg-footer.mp4') }}" type="video/mp4">
    </video>
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4);"></div>
    
    <div class="text-center text-white w-100" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2;">
        <p class="text-uppercase mb-2 fw-bold" style="letter-spacing: 2px;">SẢN PHẨM MỚI</p>
        <h2 class="display-4 fw-bold mb-4">Mùa Thu Tươi Mát</h2>
        <p class="lead mb-4 px-3">Chào mừng bạn đến với mùa vụ trái cây mới!</p>
        <a href="#" class="btn btn-outline-light btn-lg px-5 rounded-pill text-uppercase fw-bold">Xem thêm</a>
    </div>
</div>
@endsection