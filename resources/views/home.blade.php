@extends('layouts.master')
@section('content')

<style>
    .favorite-action {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 5;
    }

    .favorite-action button {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(0, 0, 0, 0.08);
        color: #dc3545;
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,.08);
    }

    .favorite-action button:hover {
        background: #dc3545;
        color: #fff;
        border-color: rgba(220,53,69,.35);
    }
</style>

{{-- 1. BANNER CAROUSEL --}}
@if(!isset($query))
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100" alt="Banner 1">
            <div class="carousel-caption">
                <h1 class="display-3 fw-bold text-uppercase">{{ __('messages.banner1_title') }}</h1>
                <p class="lead">{{ __('messages.banner1_desc') }}</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('images/banner2.jpg') }}" class="d-block w-100" alt="Banner 2">
            <div class="carousel-caption">
                <h1 class="display-3 fw-bold text-uppercase">{{ __('messages.banner2_title') }}</h1>
                <p class="lead">{{ __('messages.banner2_desc') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<div class="container py-5">

    {{-- 2. XỬ LÝ TÌM KIẾM --}}
    @if(isset($query))
    <div class="section-header mb-4">
        <h2 class="fw-bold"><i class="bi bi-search me-2"></i>{{ __('messages.search_result') }} <span class="text-success">"{{ $query }}"</span></h2>
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill">{{ __('messages.back_to_all') }}</a>
    </div>

    @if($allProducts->isEmpty())
    <div class="alert bg-body-tertiary shadow-sm border-0 rounded-4 p-5 text-center my-5">
        <h4 class="fw-bold text-body">{{ __('messages.not_found_title') }}</h4>
        <p class="text-muted">{{ __('messages.not_found_desc') }}</p>
    </div>
    @else
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mt-2">
        @foreach($allProducts as $product)
        <div class="col">
            <div class="card h-100 product-card shadow-sm border-0 position-relative">
                <span class="badge rounded-pill px-3 bg-success text-white" style="position: absolute; top: 10px; left: 10px;">
                    {{ $product->category_name ?? 'Trái cây' }}
                </span>
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="favorite-action">
                    @csrf
                    <button type="submit" class="border-0" title="{{ __('messages.add_to_cart') }}">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </form>
                <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://placehold.co/400x400?text=' . $product->name }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                    <div class="price-text mb-3 text-danger fw-bold">{{ number_format($product->price) }} VNĐ</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-success w-100 btn-sm rounded-pill">{{ __('messages.detail') }}</a>
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-success text-white w-100 btn-sm rounded-pill">{{ __('messages.buy_now') }}</a>
                    </div>
                </div>
            </div>
        </div> @endforeach
    </div>
    @endif

    {{-- 3. TRANG CHỦ BÌNH THƯỜNG --}}
    @else
    {{-- SECTION: SẢN PHẨM BÁN CHẠY --}}
    <div class="section-header d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-danger"><i class="bi bi-fire me-2"></i>{{ __('messages.hot_products') }}</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-2">
        @foreach($hotProducts as $best)
        <div class="col">
            <div class="card h-100 product-card shadow-sm border-0 position-relative">
                <span class="badge badge-hot rounded-pill p-2" style="position: absolute; top: 10px; right: 10px; background: rgba(255,0,0,0.8); color: white;">
                    <i class="bi bi-star-fill"></i> HOT
                </span>
                <form action="{{ route('cart.add', $best->id) }}" method="POST" class="favorite-action">
                    @csrf
                    <button type="submit" class="border-0" title="{{ __('messages.add_to_cart') }}">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </form>
                <img src="{{ $best->image ? asset('images/' . $best->image) : 'https://placehold.co/400x400?text=' . $best->name }}" class="card-img-top" alt="{{ $best->name }}">
                <div class="card-body text-center">
                    <p class="text-success small mb-1 fw-bold text-uppercase">{{ $best->category_name ?? 'Nội địa' }}</p>
                    <h5 class="card-title fw-bold mb-3">{{ $best->name }}</h5>
                    <div class="price-text mb-3 text-danger fw-bold">{{ number_format($best->price) }} đ</div>
                    <a href="{{ route('product.detail', $best->id) }}" class="btn btn-danger text-white w-100 py-2 rounded-pill shadow-sm">{{ __('messages.buy_now') }}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- SECTION: BỘ LỌC SẢN PHẨM (GIỮA TRANG) --}}
    <div class="filter-section my-5 p-4 shadow-sm bg-body-tertiary border rounded-4">
        <h4 class="mb-3 fw-bold text-muted text-center">{{ __('messages.filter_title') }}</h4>
        <form action="{{ route('home') }}#all-products" method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="category_id" class="form-select border-success rounded-pill">
                    <option value="">{{ __('messages.all_categories') }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="min_price" class="form-control rounded-pill" placeholder="{{ __('messages.price_from') }}" value="{{ request('min_price') }}">
            </div>
            <div class="col-md-3">
                <input type="number" name="max_price" class="form-control rounded-pill" placeholder="{{ __('messages.price_to') }}" value="{{ request('max_price') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">{{ __('messages.filter_btn') }}</button>
            </div>
        </form>
    </div>

    {{-- SECTION: TẤT CẢ SẢN PHẨM --}}
    <div id="all-products" class="section-header mt-5 mb-4">
        <h2 class="fw-bold text-success"><i class="bi bi-grid-fill me-2"></i>{{ __('messages.all_products') }}</h2>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mt-2">
        @foreach($allProducts as $product)
        <div class="col">
            <div class="card h-100 product-card shadow-sm border-0 position-relative">
                <span class="badge rounded-pill px-3 bg-success text-white" style="position: absolute; top: 10px; left: 10px;">
                    {{ $product->category_name ?? 'Trái cây' }}
                </span>
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="favorite-action">
                    @csrf
                    <button type="submit" class="border-0" title="{{ __('messages.add_to_cart') }}">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </form>
                <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://placehold.co/400x400?text=' . $product->name }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                    <div class="price-text mb-3 text-danger fw-bold">{{ number_format($product->price) }} VNĐ</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-success w-100 btn-sm rounded-pill">{{ __('messages.detail') }}</a>
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-success text-white w-100 py-2 rounded-pill shadow-sm">{{ __('messages.buy_now') }}</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- 4. VIDEO FOOTER --}}
<div class="video-footer-section position-relative w-100 mt-5" style="height: 400px; overflow: hidden;">
    <video autoplay muted loop playsinline style="width: 100%; height: 100%; object-fit: cover;">
        <source src="{{ asset('videos/bg-footer.mp4') }}" type="video/mp4">
    </video>
    <div class="video-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4);"></div>
    <div class="video-content text-center text-white position-absolute top-50 start-50 translate-middle w-100">
        <p class="text-uppercase mb-2 fw-bold" style="letter-spacing: 2px;">{{ __('messages.new_products') }}</p>
        <h2 class="display-4 fw-bold mb-4">{{ __('messages.banner1_title') }}</h2>
        <a href="#" class="btn btn-outline-light btn-lg px-5 rounded-pill text-uppercase fw-bold">{{ __('messages.see_more') }}</a>
    </div>
</div>
@endsection