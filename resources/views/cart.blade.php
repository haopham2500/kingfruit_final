@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart-check text-success me-2"></i>GIỎ HÀNG CỦA BẠN</h2>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Sản phẩm</th>
                                <th class="py-3">Giá</th>
                                <th class="py-3 text-center" style="width: 150px;">Số lượng</th>
                                <th class="py-3 text-end pe-4">Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr data-id="{{ $id }}">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $details['image'] ? asset('images/' . $details['image']) : 'https://placehold.co/50' }}" 
                                                 class="rounded-3 shadow-sm me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                <small class="text-muted">Đơn vị: {{ $details['unit'] ?? 'Kg' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">{{ number_format($details['price']) }} đ</td>
                                    <td class="py-3 text-center">
                                        <div class="input-group input-group-sm justify-content-center">
                                            <input type="number" value="{{ $details['quantity'] }}" 
                                                   class="form-control text-center update-cart" min="1" 
                                                   style="max-width: 70px; border-radius: 5px;">
                                        </div>
                                    </td>
                                    <td class="py-3 text-end pe-4 fw-bold text-danger">
                                        {{ number_format($details['price'] * $details['quantity']) }} đ
                                    </td>
                                    <td class="py-3 text-center">
                                        <button class="btn btn-sm btn-outline-danger border-0 remove-from-cart">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('home') }}" class="btn btn-outline-success rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                    <a href="{{ route('cart.clear') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Xóa sạch giỏ hàng
                    </a>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm rounded-3 p-4 bg-light text-center">
                    <h5 class="fw-bold mb-4">TỔNG THANH TOÁN</h5>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span>Tạm tính:</span>
                        <span class="fw-bold">{{ number_format($total) }} đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Tổng cộng:</span>
                        <span class="fs-4 fw-bold text-danger">{{ number_format($total) }} đ</span>
                    </div>
                    <button class="btn btn-success btn-lg w-100 rounded-pill shadow-sm fw-bold">
                        TIẾN HÀNH THANH TOÁN
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white shadow-sm rounded-3">
            <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 text-muted">Giỏ hàng của bạn đang trống</h4>
            <a href="{{ route('home') }}" class="btn btn-success mt-4 rounded-pill px-5">QUAY LẠI MUA SẮM</a>
        </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    // Cập nhật số lượng qua Ajax
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("data-id"), 
                quantity: ele.val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    // Xóa sản phẩm qua Ajax
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Bạn có muốn xóa sản phẩm này?")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
@endsection