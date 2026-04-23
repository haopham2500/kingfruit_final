@extends('layouts.master')

@section('content')
<style>
    .btn-back { display: inline-flex; align-items: center; padding: 8px 20px; background-color: #fff; color: #198754; border: 2px solid #198754; border-radius: 50px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; }
    .btn-back:hover { background-color: #198754; color: #fff; transform: translateX(-5px); }
    .product-detail-img-container { width: 100%; height: 450px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 12px; border: 1px solid #eee; }
    .product-detail-img-container img { max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.3s ease; }
    .product-detail-img-container img:hover { transform: scale(1.05); }
    .comment-item { border-bottom: 1px solid #eee; padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
    .star-rating { color: #ffc107; font-size: 0.9rem; }
</style>

<div class="container py-5">
    <div class="mb-5">
        <a href="{{ route('home') }}" class="btn-back">
            <i class="bi bi-chevron-left me-2"></i> Quay lại trang chủ
        </a>
    </div>

    <div class="row gx-5">
        <div class="col-md-6 mb-4">
            <div class="product-detail-img-container">
                {{-- Sửa: Sử dụng cột 'image' từ database kingfruit_full --}}
                <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://placehold.co/600x600?text=' . $product->name }}" alt="{{ $product->name }}">
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active">{{ $product->category_name ?? 'Trái cây' }}</li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold text-dark">{{ $product->name }}</h1>
            <p class="text-danger fs-2 fw-bold my-3">
                {{ number_format($product->price) }} VNĐ <small class="text-secondary fs-6 fw-normal">/ {{ $product->unit ?? 'Kg' }}</small>
            </p>
            <hr class="my-4">
            <div class="product-info">
                <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Mô tả sản phẩm</h5>
                <p class="text-muted" style="line-height: 1.8;">
                    {{ $product->description ?? 'Chưa có mô tả cho sản phẩm này.' }}
                </p>
            </div>
            
            <form action="#" method="POST" class="mt-4 p-4 bg-light rounded-3 shadow-sm">
                @csrf
                <div class="row align-items-center g-3">
                    <div class="col-auto"><label class="fw-bold">Số lượng</label></div>
                    <div class="col-auto">
                        <input type="number" name="quantity" value="1" min="1" class="form-control text-center shadow-none" style="width: 100px;">
                    </div>
                    <div class="col-12 col-xl-auto">
                        <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm fw-bold">
                            <i class="bi bi-cart-plus-fill me-2"></i> Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12"><hr class="my-5"><h3 class="fw-bold mb-4">Khách hàng đánh giá</h3></div>

        <div class="col-lg-7 mb-4">
            @if(isset($comments) && count($comments) > 0)
                @foreach($comments as $comment)
                <div class="comment-item">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <span class="fw-bold text-dark">{{ $comment->user_name }}</span>
                            @if(isset($comment->role) && $comment->role == 'admin') 
                                <span class="badge bg-danger ms-2">Quản trị viên</span> 
                            @endif
                        </div>
                        <small class="text-muted">{{ date('d/m/Y', strtotime($comment->created_at)) }}</small>
                    </div>
                    <div class="star-rating mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $comment->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <p class="text-secondary mb-2">{{ $comment->content }}</p>
                </div>
                @endforeach
            @else
                <div class="text-center py-5 bg-light rounded shadow-sm">
                    <i class="bi bi-chat-left-dots text-muted fs-1"></i>
                    <p class="text-muted mt-2">Chưa có đánh giá nào cho sản phẩm này.</p>
                </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Gửi đánh giá của bạn</h5>
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Đánh giá sao</label>
                            <select name="rating" class="form-select border-0 shadow-none">
                                <option value="5">5 Sao - Tuyệt vời</option>
                                <option value="4">4 Sao - Tốt</option>
                                <option value="3">3 Sao - Bình thường</option>
                                <option value="2">2 Sao - Tạm được</option>
                                <option value="1">1 Sao - Tệ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nội dung</label>
                            <textarea name="content" class="form-control border-0 shadow-none" rows="4" placeholder="Nhập cảm nhận của bạn..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">Gửi đánh giá</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection