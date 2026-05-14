@extends('layouts.master')

@section('content')
<style>
    /* CSS Tùy chỉnh để giống hệt ảnh image_85d2b9.png */
    .voucher-card {
        border: 2px dashed #198754; /* Viền xanh đứt nét bao quanh */
        border-radius: 15px;
        overflow: hidden;
        background: white;
        display: flex;
        align-items: center;
        transition: 0.3s;
    }
    .voucher-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .voucher-left { 
        width: 120px; 
        min-height: 140px; 
    }
    .voucher-right { 
        border-left: 2px dashed #eee; /* Đường gạch chia đôi voucher */
    }
    .badge-code {
        background: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
        font-family: 'Courier New', Courier, monospace;
    }
</style>

<div class="container py-5">
    <!-- Tiêu đề giống ảnh image_85d2b9.png -->
    <h2 class="text-success fw-bold mb-5 text-uppercase text-center">Kho Voucher Ưu Đãi</h2>
    
    <div class="row">
        @foreach($vouchers as $v)
        <div class="col-md-6 mb-4">
            <div class="voucher-card shadow-sm">
                <!-- Phần trái: Logo King Fruit -->
                <div class="voucher-left bg-success text-white p-3 d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-ticket-perforated fs-1"></i>
                    <span class="fw-bold small mt-1 text-center">KING FRUIT</span>
                </div>

                <!-- Phần phải: Thông tin Voucher -->
                <div class="voucher-right p-3 text-start flex-grow-1 position-relative">
                    <h5 class="fw-bold mb-1 text-success">
                        {{-- Hiển thị phần trăm hoặc số tiền tùy vào dữ liệu --}}
                        Giảm {{ number_format($v->discount_value) }}{{ $v->discount_type == 'percentage' ? '%' : 'đ' }}
                    </h5>
                    <p class="text-muted small mb-2">Đơn tối thiểu: {{ number_format($v->min_order ?? 0) }}đ</p>
                    
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <span class="badge badge-code fw-bold px-2 py-1">Mã: {{ $v->code }}</span>
                            <div class="text-danger mt-1 fw-bold" style="font-size: 11px;">
                                HSD: {{ date('d/m/Y', strtotime($v->expiry_date)) }}
                            </div>
                        </div>
                        <button class="btn btn-success btn-sm px-4 rounded-pill fw-bold">Lấy mã</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection