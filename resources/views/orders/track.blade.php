@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('messages.track_orders') }}</h1>
            <p class="text-muted mb-0">{{ __('messages.purchase_history') }}</p>
        </div>
        <a href="{{ route('profile') }}" class="btn btn-outline-secondary">{{ __('messages.profile_title') }}</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-light border text-center py-5">{{ __('messages.no_orders') }}</div>
    @else
        <div class="row g-4">
            @foreach($orders as $order)
                @php
                    $statusKey = 'order_' . str_replace('-', '_', $order->status);
                    $statusLabel = __($statusKey);
                @endphp
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary">#{{ $order->id }}</span>
                                        <span class="badge bg-secondary">{{ $statusLabel }}</span>
                                        <small class="text-muted">{{ $order->status }}</small>
                                    </div>
                                    <p class="mb-1"><strong>{{ __('messages.order_date') }}:</strong> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</p>
                                    <p class="mb-1"><strong>{{ __('messages.order_total') }}:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
                                    <p class="mb-0"><strong>{{ __('messages.shipping_address') }}:</strong> {{ $order->address ?? '-' }}</p>
                                    @if(!empty($order->cancel_reason))
                                        <p class="mb-0 mt-2 text-danger"><strong>{{ __('messages.cancel_reason') }}:</strong> {{ $order->cancel_reason }}</p>
                                    @endif
                                </div>

                                @if(in_array($order->status, ['pending', 'processing']))
                                    <div class="text-end">
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline cancel-order-form">
                                            @csrf
                                            <div class="mb-2">
                                                <label class="form-label small mb-1">{{ __('messages.cancel_reason') }}</label>
                                                <select name="reason" class="form-select form-select-sm" required>
                                                    <option value="">{{ __('messages.cancel_reason_placeholder') }}</option>
                                                    <option value="Đổi ý không mua nữa">Đổi ý không mua nữa</option>
                                                    <option value="Sản phẩm không phù hợp">Sản phẩm không phù hợp</option>
                                                    <option value="Giao hàng chậm">Giao hàng chậm</option>
                                                    <option value="Lý do khác">Lý do khác</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-sm">{{ __('messages.cancel_order') }}</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelForms = document.querySelectorAll('.cancel-order-form');
    cancelForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                },
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert(res.message || 'Có lỗi xảy ra khi hủy đơn hàng!');
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Lỗi kết nối hệ thống!');
                submitBtn.disabled = false;
            });
        });
    });
});
</script>
@endsection
