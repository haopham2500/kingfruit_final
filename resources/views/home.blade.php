@extends('layouts.master')
@section('content')

{{-- 1. CHỈ HIỂN THỊ BANNER KHI KHÔNG PHẢI ĐANG TÌM KIẾM --}}
@if(!isset($query))
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100" alt="Banner 1">
            <div class="carousel-caption">
                <h1 class="display-3 fw-bold text-uppercase">Mùa Thu Tươi Mát</h1>
                <p class="lead">Trái cây tươi ngon, bổ dưỡng từ thiên nhiên</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('images/banner2.jpg') }}" class="d-block w-100" alt="Banner 2">
            <div class="carousel-caption">
                <h1 class="display-3 fw-bold text-uppercase">Trái Cây Nhập Khẩu</h1>
                <p class="lead">Chất lượng hàng đầu, an toàn cho sức khỏe</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('images/banner3.jpg') }}" class="d-block w-100" alt="Banner 3">
            <div class="carousel-caption">
                <h1 class="display-3 fw-bold text-uppercase">Ưu Đãi Đặc Biệt</h1>
                <p class="lead">Giảm giá lên đến 30% cho các loại combo</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('images/banner4.jpg') }}" class="d-block w-100" alt="Banner 4">
            <div class="carousel-caption">
                <h1 class="display-3 fw-bold text-uppercase">Sạch Từ Nông Trại</h1>
                <p class="lead">Quy trình chăm sóc chuẩn VietGAP</p>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('images/banner5.jpg') }}" class="d-block w-100" alt="Banner 5">
            <div class="carousel-caption">
                <h1 class="display-3 fw-bold text-uppercase">Giao Hàng Tận Nơi</h1>
                <p class="lead">Ship nhanh trong vòng 2h tại nội thành</p>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
@endif

<div class="container py-5">
    
    {{-- 2. LOGIC XỬ LÝ KHI NGƯỜI DÙNG TÌM KIẾM --}}
    @if(isset($query))
        <div class="section-header mb-4">
            <h2 class="fw-bold"><i class="bi bi-search me-2"></i>KẾT QUẢ TÌM KIẾM: <span class="text-success">"{{ $query }}"</span></h2>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Quay lại tất cả sản phẩm</a>
        </div>

        @if($products->isEmpty())
            {{-- THÔNG BÁO KHÔNG TÌM THẤY --}}
            <div class="alert alert-light shadow-sm border-0 rounded-4 p-5 text-center my-5">
                <i class="bi bi- basket2-fill fs-1 text-muted d-block mb-3"></i>
                <h4 class="fw-bold text-dark">Hic, King Fruit tìm không ra rồi ní ơi!</h4>
                <p class="text-muted">Ní thử tìm tên khác (ví dụ: Táo, Nho, Lê...) xem sao nhé.</p>
            </div>
        @else
            {{-- HIỂN THỊ KẾT QUẢ TÌM ĐƯỢC --}}
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mt-2">
                @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 product-card shadow-sm border-0">
                        <span class="badge badge-cate rounded-pill px-3 bg-success text-white">{{ $product->category_name ?? 'Trái cây' }}</span>
                        <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://placehold.co/400x400?text=' . $product->name }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                            <div class="price-text mb-3 text-danger fw-bold">{{ number_format($product->price) }} VNĐ</div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-success w-100 btn-sm rounded-pill shadow-sm">Chi tiết</a>
                                <button class="btn btn-success w-100 btn-sm rounded-pill shadow-sm">Mua ngay</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    {{-- 3. HIỂN THỊ TRANG CHỦ BÌNH THƯỜNG (KHI KHÔNG TÌM KIẾM) --}}
    @else
        <div class="section-header d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold"><i class="bi bi-fire text-danger me-2"></i>SẢN PHẨM BÁN CHẠY</h2>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-2">
            @foreach($bestSellers as $best)
            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <span class="badge badge-hot rounded-pill p-2"><i class="bi bi-star-fill"></i> HOT</span>
                    <img src="{{ $best->image ? asset('images/' . $best->image) : 'https://placehold.co/400x400?text=' . $best->name }}" class="card-img-top" alt="{{ $best->name }}">
                    <div class="card-body text-center">
                        <p class="text-success small mb-1 fw-bold text-uppercase">{{ $best->category_name ?? 'Nội địa' }}</p>
                        <h5 class="card-title fw-bold mb-3">{{ $best->name }}</h5>
                        <div class="price-text mb-3 text-danger fw-bold">{{ number_format($best->price) }} đ</div>
                        <a href="{{ route('product.detail', $best->id) }}" class="btn btn-danger text-white w-100 py-2 rounded-pill shadow-sm">Mua ngay</a>
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
                {{-- (Nội dung card tất cả sản phẩm giữ nguyên như trên) --}}
                <div class="col">
                    <div class="card h-100 product-card shadow-sm border-0">
                        <span class="badge badge-cate rounded-pill px-3 bg-success text-white">{{ $product->category_name ?? 'Trái cây' }}</span>
                        <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://placehold.co/400x400?text=' . $product->name }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                            <div class="price-text mb-3 text-danger fw-bold">{{ number_format($product->price) }} VNĐ</div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-success w-100 btn-sm rounded-pill shadow-sm">Chi tiết</a>
                                <button class="btn btn-success w-100 btn-sm rounded-pill shadow-sm">Mua ngay</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- 4. VIDEO FOOTER (LUÔN HIỂN THỊ) --}}
<div class="video-footer-section position-relative w-100">
    <div class="video-container">
        <video autoplay muted loop playsinline class="w-100 h-100">
            <source src="{{ asset('videos/bg-footer.mp4') }}" type="video/mp4">
        </video>
        <div class="video-overlay"></div>
    </div>
    <div class="video-content text-center text-white w-100">
        <p class="text-uppercase mb-2 fw-bold" style="letter-spacing: 2px;">SẢN PHẨM MỚI</p>
        <h2 class="display-3 fw-bold mb-4">Mùa Thu Tươi Mát</h2>
        <p class="lead mb-4 px-3">Chào mừng bạn đến với mùa vụ trái cây mới!</p>
        <a href="#" class="btn btn-outline-light btn-lg px-5 rounded-pill text-uppercase fw-bold">Xem thêm</a>
    </div>
</div>
@endsection