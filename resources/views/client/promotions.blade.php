@extends('layouts.master')

@section('content')
<style>
    .voucher-card {
        border: 2px dashed #198754;
        border-radius: 15px;
        overflow: hidden;
        background: white;
        height: 100%; 
        display: flex;
        align-items: center;
        transition: 0.3s;
    }
    .voucher-card:hover:not(.expired) {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .voucher-left { width: 120px; min-height: 140px; }
    .voucher-right { border-left: 2px dashed #eee; flex: 1; }
    .expired { 
        opacity: 0.6; 
        filter: grayscale(1); 
        border-color: #6c757d !important;
    }
    .badge-code {
        background: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
        font-family: 'Courier New', Courier, monospace;
    }
</style>

<div class="container py-5">
    <h2 class="text-success fw-bold mb-5 text-uppercase text-center">Kho Voucher Ưu Đãi</h2>
    
    <div class="row"> 
        @foreach($vouchers as $v)
            @php
                $isExpired = \Carbon\Carbon::parse($v->expiry_date)->isPast();
                $isCollected = in_array($v->code, session()->get('collected_vouchers', []));
            @endphp
            
            <div class="col-md-6 mb-4">
                <div class="voucher-card shadow-sm {{ $isExpired ? 'expired' : '' }}">
                    <div class="voucher-left {{ $isExpired ? 'bg-secondary' : 'bg-success' }} text-white p-3 d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-ticket-perforated fs-1"></i>
                        <span class="fw-bold small mt-1 text-center">KING FRUIT</span>
                    </div>

                    <div class="voucher-right p-3 text-start position-relative">
                        <h5 class="fw-bold mb-1 {{ $isExpired ? 'text-secondary' : 'text-success' }}">
                            Giảm {{ number_format($v->discount_value) }}{{ $v->discount_type == 'percentage' ? '%' : 'đ' }}
                        </h5>
                        <p class="text-muted small mb-2">Đơn tối thiểu: {{ number_format($v->min_order ?? 0) }}đ</p>
                        
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <span class="badge badge-code fw-bold px-2 py-1">Mã: {{ $v->code }}</span>
                                <div class="text-danger mt-1 fw-bold" style="font-size: 11px;">
                                    HSD: {{ date('d/m/Y', strtotime($v->expiry_date)) }}
                                    @if($isExpired) <span class="badge bg-danger ms-1">HẾT HẠN</span> @endif
                                </div>
                            </div>
                            
                            @if($isExpired)
                                <button class="btn btn-secondary btn-sm px-3 rounded-pill disabled" disabled>Hết hạn</button>
                            @elseif($isCollected)
                                <button class="btn btn-outline-secondary btn-sm px-4 rounded-pill disabled" disabled>Đã lấy</button>
                            @else
                                <button class="btn btn-success btn-sm px-4 rounded-pill fw-bold collect-btn" data-code="{{ $v->code }}">Lấy mã</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div> 
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.collect-btn').click(function() {
            let btn = $(this);
            let code = btn.data('code');

            $.ajax({
                url: '{{ route("voucher.collect") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    code: code
                },
                success: function(res) {
                    btn.removeClass('btn-success').addClass('btn-outline-secondary').text('Đã lấy').prop('disabled', true);
                    alert('Ngon! Đã thu thập mã ' + code + '. Qua trang thanh toán dùng nhé ní!');
                }
            });
        });
    });
</script>
@endsection