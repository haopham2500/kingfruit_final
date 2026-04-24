@extends('layouts.master')

@section('content')
<div class="container py-5" style="min-height: 600px;">
    <h2 class="fw-bold mb-4 text-uppercase">
        <i class="bi bi-cart-check text-success me-2"></i>Giỏ hàng của bạn
    </h2>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row" id="cart-container">
            {{-- Danh sách sản phẩm --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-uppercase small fw-bold text-muted">
                                    <th class="ps-4 py-3">Sản phẩm</th>
                                    <th class="py-3">Giá</th>
                                    <th class="py-3 text-center" style="width: 130px;">Số lượng</th>
                                    <th class="py-3 text-end pe-4">Thành tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr data-id="{{ $id }}" class="cart-item-row">
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    <img src="{{ $details['image'] ? asset('images/' . $details['image']) : 'https://placehold.co/80x80?text=Fruit' }}" 
                                                         class="rounded-3 shadow-sm" 
                                                         style="width: 70px; height: 70px; object-fit: cover;">
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $details['name'] }}</h6>
                                                    <span class="badge bg-light text-success border border-success-subtle fw-normal">
                                                        Đơn vị: {{ $details['unit'] ?? 'Kg' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 text-muted fw-semibold">
                                            {{ number_format($details['price']) }} đ
                                        </td>
                                        <td class="py-4 text-center">
                                            <div class="input-group input-group-sm justify-content-center shadow-sm rounded-pill overflow-hidden border" style="max-width: 110px; margin: 0 auto;">
                                                <button class="btn btn-white border-0 px-2 btn-minus" type="button"><i class="bi bi-dash"></i></button>
                                                <input type="number" value="{{ $details['quantity'] }}" 
                                                       class="form-control text-center border-0 update-cart fw-bold" 
                                                       min="1" style="width: 40px;">
                                                <button class="btn btn-white border-0 px-2 btn-plus" type="button"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </td>
                                        <td class="py-4 text-end pe-4 fw-bold text-danger fs-5">
                                            {{ number_format($details['price'] * $details['quantity']) }} đ
                                        </td>
                                        <td class="py-4 text-center pe-3">
                                            <button class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-from-cart" 
                                                    style="width: 35px; height: 35px;" title="Xóa món này">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-link text-success text-decoration-none fw-bold p-0">
                        <i class="bi bi-chevron-left me-1"></i> Quay lại cửa hàng
                    </a>
                    <a href="{{ route('cart.clear') }}" class="btn btn-light rounded-pill px-4 text-muted small shadow-sm" 
                       onclick="return confirm('Ní muốn dọn sạch giỏ hàng thật hả?')">
                        Làm trống giỏ hàng
                    </a>
                </div>
            </div>

            {{-- Checkout Card --}}
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4">Chi tiết hóa đơn</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-semibold">{{ number_format($total) }} đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-success fw-semibold">Miễn phí</span>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold text-dark">Tổng cộng:</span>
                        <span class="fs-4 fw-bold text-danger">{{ number_format($total) }} đ</span>
                    </div>
                    <button class="btn btn-success btn-lg w-100 rounded-pill shadow-sm fw-bold py-3 mb-3 btn-checkout">
                        TIẾN HÀNH ĐẶT HÀNG
                    </button>
                    <div class="bg-light p-3 rounded-3 text-center">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Đơn hàng sẽ được giao trong 24h.</small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white shadow-sm rounded-5 my-5 border border-dashed border-2">
            <div class="mb-4">
                <i class="bi bi-basket3 text-success opacity-25" style="font-size: 7rem;"></i>
            </div>
            <h3 class="fw-bold text-dark">Giỏ hàng đang trống!</h3>
            <p class="text-muted mb-4 px-4">Hiện tại bạn chưa chọn sản phẩm nào cho vào giỏ hàng cả.</p>
            <a href="{{ route('home') }}" class="btn btn-success btn-lg rounded-pill px-5 shadow fw-bold">CHỌN TRÁI CÂY NGAY</a>
        </div>
    @endif
</div>

{{-- Script xử lý Ajax --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
        // Nút cộng số lượng
        $(".btn-plus").click(function() {
            let input = $(this).siblings('.update-cart');
            let newVal = parseInt(input.val()) + 1;
            input.val(newVal).trigger('change');
        });

        // Nút trừ số lượng
        $(".btn-minus").click(function() {
            let input = $(this).siblings('.update-cart');
            let newVal = parseInt(input.val()) - 1;
            if(newVal >= 1) {
                input.val(newVal).trigger('change');
            }
        });

        // Cập nhật giỏ hàng khi input thay đổi (bao gồm cả khi nhấn cộng/trừ)
        $(".update-cart").on('change', function () {
            let ele = $(this);
            let quantity = ele.val();
            let id = ele.closest("tr").attr("data-id");

            if(quantity < 1) {
                alert('Số lượng tối thiểu là 1 ní ơi!');
                ele.val(1);
                return;
            }

            $.ajax({
                url: '{{ route("cart.update") }}',
                method: "PATCH",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id, 
                    quantity: quantity
                },
                beforeSend: function() {
                    ele.closest('tr').css('opacity', '0.5');
                },
                success: function (response) {
                    // Load lại để cập nhật tổng tiền và UI cho chuẩn
                    window.location.reload();
                },
                error: function() {
                    alert('Lỗi cập nhật rồi ní!');
                    window.location.reload();
                }
            });
        });

        // Xóa sản phẩm khỏi giỏ
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
            let ele = $(this);
            let id = ele.closest("tr").attr("data-id");

            if(confirm("Ní thực sự muốn bỏ sản phẩm này ra khỏi giỏ?")) {
                $.ajax({
                    url: '{{ route("cart.remove") }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: id
                    },
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function() {
                        alert('Xóa không được rồi ní!');
                    }
                });
            }
        });

        // Nút tiến hành đặt hàng (chỗ này ní có thể redirect tới trang thanh toán sau này)
        $(".btn-checkout").click(function() {
            alert('Tính năng đặt hàng đang được "Vua Trái Cây" phát triển nhé ní!');
        });
    });
</script>
@endsection