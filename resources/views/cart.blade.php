@extends('layouts.master')

@section('content')
<div class="container py-5" style="min-height: 600px;">
    <h2 class="fw-bold mb-4 text-uppercase">
        <i class="bi bi-cart-check text-success me-2"></i>{{ __('messages.cart_title') }}
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
                                    <th class="ps-4 py-3">{{ __('messages.product_name') }}</th>
                                    <th class="py-3">{{ __('messages.product_price') }}</th>
                                    <th class="py-3 text-center" style="width: 130px;">{{ __('messages.quantity') }}</th>
                                    <th class="py-3 text-end pe-4">{{ __('messages.total') }}</th>
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
                                                    <h6 class="mb-1 fw-bold text-body">{{ $details['name'] }}</h6>
                                                    <span class="badge bg-body-tertiary text-success border border-success-subtle fw-normal">
                                                        {{ __('messages.product_unit') }}: {{ $details['unit'] ?? __('messages.unit_kg') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 text-muted fw-semibold item-price" data-price="{{ $details['price'] }}">
                                            {{ number_format($details['price']) }} {{ __('messages.currency') }}
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
                                        <td class="py-4 text-end pe-4 fw-bold text-danger fs-5 item-total">
                                            {{ number_format($details['price'] * $details['quantity']) }} {{ __('messages.currency') }}
                                        </td>
                                        <td class="py-4 text-center pe-3">
                                            <button class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-from-cart" 
                                                    style="width: 35px; height: 35px;" title="{{ __('messages.remove_item') }}">
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
                        <i class="bi bi-chevron-left me-1"></i> {{ __('messages.back_to_shop') }}
                    </a>
                    <a href="{{ route('cart.clear') }}" class="btn btn-light rounded-pill px-4 text-muted small shadow-sm" 
                       onclick="return confirm('{{ __('messages.clear_cart_confirm') }}')">
                        {{ __('messages.clear_cart') }}
                    </a>
                </div>
            </div>

            {{-- Checkout Card --}}
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4">{{ __('messages.order_detail') }}</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">{{ __('messages.subtotal') }}:</span>
                        <span class="fw-semibold subtotal-display">{{ number_format($total) }} {{ __('messages.currency') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">{{ __('messages.shipping_fee') }}:</span>
                        <span class="text-success fw-semibold">{{ __('messages.free_shipping') }}</span>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold text-body">{{ __('messages.total') }}:</span>
                        <span class="fs-4 fw-bold text-danger total-display">{{ number_format($total) }} {{ __('messages.currency') }}</span>
                    </div>
<a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm fw-bold py-3 mb-3 text-decoration-none d-flex align-items-center justify-content-center">
    {{ __('messages.proceed_checkout') }}
</a>
                    <div class="bg-body-tertiary p-3 rounded-3 text-center">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> {{ __('messages.order_will_deliver') }}</small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-body-tertiary shadow-sm rounded-5 my-5 border border-dashed border-2">
            <div class="mb-4">
                <i class="bi bi-basket3 text-success opacity-25" style="font-size: 7rem;"></i>
            </div>
            <h3 class="fw-bold text-body">{{ __('messages.cart_empty') }}</h3>
            <p class="text-muted mb-4 px-4">{{ __('messages.order_empty_message') }}</p>
            <a href="{{ route('home') }}" class="btn btn-success btn-lg rounded-pill px-5 shadow fw-bold">{{ __('messages.shop_now') }}</a>
        </div>
    @endif
</div>

{{-- Script xử lý Ajax --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    const quantityMinimumMessage = @json(__('messages.quantity_minimum'));
    const updateErrorMessage = @json(__('messages.update_error'));
    const removeErrorMessage = @json(__('messages.remove_error'));
    const removeItemConfirmMessage = @json(__('messages.delete_item_confirm'));
    const currencySymbol = @json(__('messages.currency'));

    $(document).ready(function() {
        let debounceTimer;

        // Hàm tính toán và cập nhật giá trị hiển thị trên UI tức thời
        function updateTotals() {
            let total = 0;
            $(".cart-item-row").each(function() {
                let row = $(this);
                let price = parseInt(row.find('.item-price').attr('data-price'));
                let quantity = parseInt(row.find('.update-cart').val()) || 0;
                let itemTotal = price * quantity;
                
                row.find('.item-total').text(itemTotal.toLocaleString() + ' ' + currencySymbol);
                total += itemTotal;
            });
            
            $('.subtotal-display').text(total.toLocaleString() + ' ' + currencySymbol);
            $('.total-display').text(total.toLocaleString() + ' ' + currencySymbol);
        }

        // Hàm gửi AJAX cập nhật giỏ hàng lên server (Debounced)
        function sendCartUpdate(id, quantity, row) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route("cart.update") }}',
                    method: "PATCH",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: id, 
                        quantity: quantity
                    },
                    beforeSend: function() {
                        row.css('opacity', '0.7');
                    },
                    success: function (response) {
                        row.css('opacity', '1');
                    },
                    error: function() {
                        row.css('opacity', '1');
                        alert(updateErrorMessage);
                    }
                });
            }, 500); // Đợi 500ms sau lần gõ cuối mới gửi request
        }
        
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

        // Bắt sự kiện gõ phím (input) và thay đổi giá trị (change)
        $(document).on('input change', '.update-cart', function () {
            let ele = $(this);
            let quantity = parseInt(ele.val()) || 0;
            let id = ele.closest("tr").attr("data-id");
            let row = ele.closest("tr");

            if(quantity < 1) {
                // Cho phép người dùng tạm xóa để gõ số mới, không chặn ngay lúc đang gõ
                return;
            }

            // Cập nhật giá trị hiển thị trên UI ngay lập tức
            updateTotals();

            // Gửi cập nhật ngầm lên server
            sendCartUpdate(id, quantity, row);
        });

        // Xử lý khi người dùng rời ô nhập liệu (blur) mà bỏ trống hoặc nhập số không hợp lệ
        $(document).on('blur', '.update-cart', function() {
            let ele = $(this);
            let quantity = parseInt(ele.val()) || 0;
            let id = ele.closest("tr").attr("data-id");
            let row = ele.closest("tr");

            if (quantity < 1) {
                alert(quantityMinimumMessage);
                ele.val(1);
                updateTotals();
                sendCartUpdate(id, 1, row);
            }
        });

        // Xóa sản phẩm khỏi giỏ
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
            let ele = $(this);
            let id = ele.closest("tr").attr("data-id");

            if(confirm(removeItemConfirmMessage)) {
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
                        alert(removeErrorMessage);
                    }
                });
            }
        });
    });
</script>
@endsection