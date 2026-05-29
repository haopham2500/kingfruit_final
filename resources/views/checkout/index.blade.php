@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4 text-success text-uppercase">
        <i class="bi bi-cart-check me-2"></i>{{ __('messages.checkout_title') }}
    </h2>

    <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkout-form">
        @csrf
        {{-- Thêm input hidden để gửi mã voucher khi submit form đặt hàng --}}
        <input type="hidden" name="voucher_code" id="applied_voucher_code">

        <div class="row">
            <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG -->
            <div class="col-md-7">
                <div class="card shadow-sm border-0 p-4 rounded-4">
                    <h5 class="fw-bold mb-4">{{ __('messages.shipping_info') }}</h5>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.customer_name') }}</label>
                        <input type="text" name="customer_name" class="form-control" required placeholder="{{ __('messages.customer_name_placeholder') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.customer_phone') }}</label>
                        <input type="text" name="phone" class="form-control" required placeholder="{{ __('messages.customer_phone_placeholder') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.shipping_address') }}</label>
                        <textarea name="address" rows="3" class="form-control" required placeholder="{{ __('messages.shipping_address_placeholder') }}"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.order_notes') }}</label>
                        <textarea name="note" rows="2" class="form-control" placeholder="{{ __('messages.order_note_placeholder') }}"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">{{ __('messages.payment_method') }}</label>
                        <select name="payment_method" class="form-select">
                            <option value="cod">{{ __('messages.cash_on_delivery') }}</option>
                            <option value="banking">{{ __('messages.bank_transfer') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG & VOUCHER -->
            <div class="col-md-5 mt-4 mt-md-0">
                <div class="card shadow-sm border-0 p-4 rounded-4">
                    <h5 class="fw-bold mb-4">{{ __('messages.checkout_order_title') }}</h5>

                    @php $total = 0; @endphp
                    @foreach(session('cart', []) as $id => $item)
                        @php 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                            <span class="fw-bold">{{ number_format($subtotal) }} {{ __('messages.currency') }}</span>
                        </div>
                    @endforeach

                    <hr class="my-4 opacity-50">

                    <!-- PHẦN CHỌN VOUCHER -->
                    <label class="form-label fw-bold small">{{ __('messages.voucher_code') }}</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control border-end-0" placeholder="{{ __('messages.voucher_code_placeholder') }}" id="voucher_code">
                        <button class="btn btn-dark px-3 fw-bold" type="button" id="apply_voucher_btn">{{ __('messages.apply_voucher') }}</button>
                    </div>
                    <div class="text-end mb-4">
                        <a href="javascript:void(0)" class="text-success small text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#modalVoucher">
                            <i class="bi bi-ticket-perforated me-1"></i> {{ __('messages.choose_voucher') }}
                        </a>
                    </div>

                    <!-- TÍNH TOÁN TIỀN -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('messages.subtotal') }}:</span>
                        <span class="fw-semibold" id="subtotal_val" data-value="{{ $total }}">{{ number_format($total) }} {{ __('messages.currency') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>{{ __('messages.discount') }}:</span>
                        <span id="discount_display">-0 {{ __('messages.currency') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">{{ __('messages.total') }}:</span>
                        {{-- Sửa class ở đây để khớp với CSS của bạn --}}
                        <span class="fs-4 fw-bold text-danger" id="total_final_display">{{ number_format($total) }} {{ __('messages.currency') }}</span>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-sm">
                        {{ __('messages.confirm_order') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL VOUCHER -->
<div class="modal fade" id="modalVoucher" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-success text-white border-0 rounded-top-4">
                <h6 class="modal-title fw-bold"><i class="bi bi-gift-fill me-2"></i>{{ __('messages.voucher_modal_title') }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-body-tertiary">
                @foreach($vouchers as $v)
                @php
                    $isUsed = in_array($v->code, session()->get('used_vouchers', []));
                @endphp
                <div class="bg-body-tertiary border-success-subtle border-2 border p-3 rounded-3 d-flex justify-content-between align-items-center mb-3 shadow-sm" style="border-style: dashed !important;">
                    <div>
                        <div class="fw-bold text-success">{{ __('messages.discount') }} {{ number_format($v->discount_value) }}{{ $v->discount_type == 'percentage' ? '%' : __('messages.currency') }}</div>
                        <div class="small fw-bold text-body">{{ __('messages.voucher_code') }}: {{ $v->code }}</div>
                        <div class="text-muted" style="font-size: 11px;">{{ __('messages.voucher_expiry') }}: {{ $v->expiry_date }}</div>
                    </div>
                    @if($isUsed)
                        <button type="button" class="btn btn-secondary btn-sm px-3 fw-bold rounded-pill disabled" disabled>{{ __('messages.voucher_used_up') }}</button>
                    @else
                        <button type="button" class="btn btn-success btn-sm px-3 fw-bold rounded-pill btn-use-voucher" data-code="{{ $v->code }}">{{ __('messages.use_voucher') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    const voucherRequiredMessage = @json(__('messages.please_enter_voucher'));
    const currencySymbol = @json(__('messages.currency'));

    $(document).ready(function() {
        // Khi nhấn nút "Dùng" trong Modal
        $(document).on('click', '.btn-use-voucher', function() {
            let code = $(this).data('code');
            $('#voucher_code').val(code);
            $('#modalVoucher').modal('hide');
            $('#apply_voucher_btn').click(); // Tự động nhấn áp dụng sau khi chọn
        });

        // Nút Áp dụng Ajax
        $('#apply_voucher_btn').click(function() {
            let voucherCode = $('#voucher_code').val();
            let subtotal = parseInt($('#subtotal_val').attr('data-value'));

            if (voucherCode == "") {
                alert(voucherRequiredMessage);
                return;
            }

            $.ajax({
                url: '{{ route("voucher.apply") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    code: voucherCode
                },
                success: function(res) {
                    if(res.success) {
        // ... Cập nhật tiền ...
    } else {
        // Đây chính là nơi hiển thị thông báo "Mã giảm giá này đã hết hạn sử dụng!" 
        // mà Controller gửi về.
        alert(res.message); 
    }
                    if (res.success) {
                        let discount = parseInt(res.discount);
                        
                        // CHẶN LỖI GIÁ ÂM: Nếu giảm giá > tiền hàng, thì chỉ giảm tối đa bằng tiền hàng
                        let actualDiscount = (discount > subtotal) ? subtotal : discount;
                        let newTotal = subtotal - actualDiscount;

                        // Cập nhật giao diện
                        $('#discount_display').text('-' + actualDiscount.toLocaleString() + ' ' + currencySymbol);
                        $('#total_final_display').text(newTotal.toLocaleString() + ' ' + currencySymbol);
                        
                        // Lưu mã voucher vào hidden input để gửi cùng form đặt hàng
                        $('#applied_voucher_code').val(voucherCode);

                        alert(res.message);
                    } else {
                        alert(res.message);
                        // Reset giao diện nếu mã sai
                        $('#discount_display').text('-0 ' + currencySymbol);
                        $('#total_final_display').text(subtotal.toLocaleString() + ' ' + currencySymbol);
                        $('#applied_voucher_code').val('');
                    }
                },
                error: function() {
                    alert('Lỗi kết nối hệ thống!');
                }
            });
        });

        // Xử lý submit form đặt hàng bằng AJAX
        $('#checkout-form').submit(function(e) {
            e.preventDefault();
            
            let form = $(this);
            let submitBtn = form.find('button[type="submit"]');
            
            // Disable nút submit để tránh click nhiều lần
            submitBtn.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(res) {
                    if (res.success) {
                        alert(res.message);
                        window.location.href = res.redirect_url;
                    } else {
                        alert(res.message || 'Có lỗi xảy ra khi đặt hàng!');
                        submitBtn.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Lỗi kết nối hệ thống!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                    submitBtn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection