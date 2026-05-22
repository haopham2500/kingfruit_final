@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4 text-success text-uppercase">
        <i class="bi bi-cart-check me-2"></i>Thanh toán đơn hàng
    </h2>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf
        {{-- Thêm input hidden để gửi mã voucher khi submit form đặt hàng --}}
        <input type="hidden" name="voucher_code" id="applied_voucher_code">

        <div class="row">
            <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG -->
            <div class="col-md-7">
                <div class="card shadow-sm border-0 p-4 rounded-4">
                    <h5 class="fw-bold mb-4">Thông tin giao hàng</h5>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và tên</label>
                        <input type="text" name="customer_name" class="form-control" required placeholder="Nhập họ tên">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" required placeholder="Nhập số điện thoại">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ giao hàng</label>
                        <textarea name="address" rows="3" class="form-control" required placeholder="Số nhà, tên đường, phường/xã..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <textarea name="note" rows="2" class="form-control" placeholder="Ghi chú thêm về đơn hàng"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Phương thức thanh toán</label>
                        <select name="payment_method" class="form-select">
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="banking">Chuyển khoản ngân hàng</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG & VOUCHER -->
            <div class="col-md-5 mt-4 mt-md-0">
                <div class="card shadow-sm border-0 p-4 rounded-4">
                    <h5 class="fw-bold mb-4">Đơn hàng của bạn</h5>

                    @php $total = 0; @endphp
                    @foreach(session('cart', []) as $id => $item)
                        @php 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                            <span class="fw-bold">{{ number_format($subtotal) }} đ</span>
                        </div>
                    @endforeach

                    <hr class="my-4 opacity-50">

                    <!-- PHẦN CHỌN VOUCHER -->
                    <label class="form-label fw-bold small">Mã giảm giá</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control border-end-0" placeholder="Nhập mã" id="voucher_code">
                        <button class="btn btn-dark px-3 fw-bold" type="button" id="apply_voucher_btn">Áp dụng</button>
                    </div>
                    <div class="text-end mb-4">
                        <a href="javascript:void(0)" class="text-success small text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#modalVoucher">
                            <i class="bi bi-ticket-perforated me-1"></i> Chọn mã ưu đãi
                        </a>
                    </div>

                    <!-- TÍNH TOÁN TIỀN -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-semibold" id="subtotal_val" data-value="{{ $total }}">{{ number_format($total) }} đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá:</span>
                        <span id="discount_display">-0 đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Tổng cộng:</span>
                        {{-- Sửa class ở đây để khớp với CSS của bạn --}}
                        <span class="fs-4 fw-bold text-danger" id="total_final_display">{{ number_format($total) }} đ</span>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-sm">
                        XÁC NHẬN ĐẶT HÀNG
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
                <h6 class="modal-title fw-bold"><i class="bi bi-gift-fill me-2"></i>Mã Giảm Giá King Fruit</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-body-tertiary">
                @foreach($vouchers as $v)
                <div class="bg-body-tertiary border-success-subtle border-2 border p-3 rounded-3 d-flex justify-content-between align-items-center mb-3 shadow-sm" style="border-style: dashed !important;">
                    <div>
                        <div class="fw-bold text-success">Giảm {{ number_format($v->discount_value) }}đ</div>
                        <div class="small fw-bold text-body">Mã: {{ $v->code }}</div>
                        <div class="text-muted" style="font-size: 11px;">HSD: {{ $v->expiry_date }}</div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm px-3 fw-bold rounded-pill btn-use-voucher" data-code="{{ $v->code }}">Dùng</button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
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
                alert("Vui lòng nhập mã giảm giá");
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
                        $('#discount_display').text('-' + actualDiscount.toLocaleString() + ' đ');
                        $('#total_final_display').text(newTotal.toLocaleString() + ' đ');
                        
                        // Lưu mã voucher vào hidden input để gửi cùng form đặt hàng
                        $('#applied_voucher_code').val(voucherCode);

                        alert(res.message);
                    } else {
                        alert(res.message);
                        // Reset giao diện nếu mã sai
                        $('#discount_display').text('-0 đ');
                        $('#total_final_display').text(subtotal.toLocaleString() + ' đ');
                        $('#applied_voucher_code').val('');
                    }
                },
                error: function() {
                    alert('Lỗi kết nối hệ thống!');
                }
            });
        });
    });
</script>
@endsection