@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="fw-bold">Quản lý bình luận</h2>
        {{-- Giữ nguyên nút thêm hoặc các chức năng phụ khác --}}
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4 py-3">Khách hàng / Liên hệ</th>
                            <th class="py-3">Nội dung đánh giá</th>
                            <th class="py-3">Lịch sử phản hồi</th>
                            <th class="py-3 text-end pe-4">Xử lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-body">{{ $review->user?->name }}</span>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                    <div class="mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill small {{ $i <= $review->rating ? 'text-warning' : 'text-light' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 text-secondary small" style="max-width: 300px;">{{ $review->comment }}</p>
                            </td>
                            <td style="width: 35%;">
                                {{-- Danh sách các câu đã trả lời --}}
                                <div class="reply-container mb-2">
                                    @foreach($review->replies as $reply)
                                    <div class="p-2 mb-2 bg-body-tertiary rounded border-start border-success border-3 shadow-xs">
                                        <div class="d-flex justify-content-between">
                                            <span class="badge bg-success-soft text-success mb-1" style="font-size: 0.7rem;">ADMIN PHẢN HỒI</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $reply->created_at->format('H:i d/m') }}</small>
                                        </div>
                                        <p class="mb-0 small text-body italic">"{{ $reply->comment }}"</p>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Form trả lời nhanh --}}
                                <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="reply_content" class="form-control shadow-none" placeholder="Nhập nội dung phản hồi..." required>
                                        <button class="btn btn-success" type="submit">
                                            <i class="bi bi-send-fill"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Nút xem chi tiết sản phẩm nếu cần --}}
                                    <a href="{{ route('product.detail', $review->product_id) }}" class="btn btn-outline-primary btn-sm" title="Xem sản phẩm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Xóa bình luận này sẽ mất luôn các phản hồi liên quan, ní chắc chưa?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom CSS để giống trang quản lý đơn hàng */
    .bg-success-soft { background-color: #e8f5e9; }
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.04)!important; }
    .table thead th {
        border-top: none;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    .italic { font-style: italic; color: #555; }
</style>
@endsection