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
                    $statusLabel = __('messages.' . $statusKey);

                    $statusColors = [
                        'pending'         => 'bg-warning text-dark',
                        'processing'      => 'bg-info text-white',
                        'completed'       => 'bg-success text-white',
                        'cancelled'       => 'bg-danger text-white',
                        'refunded'        => 'bg-primary text-white',
                        'wait_refund'     => 'bg-dark text-white',
                        'refund_rejected' => 'bg-danger text-white',
                    ];
                    $badgeClass = $statusColors[$order->status] ?? 'bg-secondary text-white';
                @endphp
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary">#{{ $order->id }}</span>
                                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                    </div>
                                    <p class="mb-1"><strong>{{ __('messages.order_date') }}:</strong> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</p>
                                    <p class="mb-1"><strong>{{ __('messages.order_total') }}:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
                                    <p class="mb-0"><strong>{{ __('messages.shipping_address') }}:</strong> {{ $order->address ?? '-' }}</p>
                                    @if(!empty($order->cancel_reason))
                                        <p class="mb-0 mt-2 text-danger"><strong>{{ __('messages.cancel_reason') }}:</strong> {{ $order->cancel_reason }}</p>
                                    @endif
                                    @if(in_array($order->status, ['wait_refund', 'refunded', 'refund_rejected']))
                                        <div class="mt-3 p-3 bg-light rounded border text-start">
                                            <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-arrow-counterclockwise me-1"></i> Thông tin Trả hàng / Hoàn tiền</h6>
                                            <p class="mb-1 small text-muted"><strong>{{ __('messages.refund_reason') }}:</strong> {{ $order->refund_reason ?? 'Không có lý do' }}</p>
                                            @if($order->refund_evidence)
                                                <p class="mb-1 small">
                                                    <strong>{{ __('messages.refund_evidence') }}:</strong> 
                                                    <a href="{{ asset('refunds/' . $order->refund_evidence) }}" target="_blank" class="text-decoration-none text-primary">
                                                        <i class="bi bi-image me-1"></i> Xem minh chứng
                                                    </a>
                                                </p>
                                            @endif
                                            @if($order->refund_feedback)
                                                <p class="mb-0 small text-danger mt-2">
                                                    <strong>{{ __('messages.refund_feedback') }}:</strong> {{ $order->refund_feedback }}
                                                </p>
                                            @endif
                                        </div>
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

                                @if($order->status === 'completed' && $order->created_at && $order->created_at->gt(now()->subDays(14)))
                                    <div class="text-end">
                                        <button type="button" class="btn btn-warning btn-sm btn-trigger-refund" data-order-id="{{ $order->id }}" data-action="{{ route('orders.refund', $order->id) }}" data-bs-toggle="modal" data-bs-target="#refundModal">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Trả hàng & Hoàn tiền
                                        </button>
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
</div>

<!-- Modal Yêu Cầu Hoàn Tiền -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-warning text-dark border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="refundModalLabel">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Yêu cầu Trả hàng / Hoàn tiền
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="refund-form" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.refund_reason') }} <span class="text-danger">*</span></label>
                        <textarea name="refund_reason" class="form-control" rows="3" required placeholder="Ví dụ: Trái cây bị hỏng/dập khi nhận hàng..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.refund_evidence') }} <span class="text-danger">*</span></label>
                        <input type="file" name="evidence" class="form-control" accept="image/*" required>
                        <div class="form-text text-muted small">Vui lòng tải lên hình ảnh minh chứng tình trạng sản phẩm (tối đa 2MB).</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Gửi yêu cầu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === XỬ LÝ HỦY ĐƠN HÀNG ===
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
 
    // === XỬ LÝ CLICK MỞ MODAL HOÀN TIỀN ===
    const refundButtons = document.querySelectorAll('.btn-trigger-refund');
    const refundForm = document.getElementById('refund-form');
    refundButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const actionUrl = this.getAttribute('data-action');
            refundForm.setAttribute('action', actionUrl);
            refundForm.reset();
        });
    });

    // === XỬ LÝ GỬI FORM HOÀN TIỀN LÊN SERVER ===
    if (refundForm) {
        refundForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Bạn có chắc chắn muốn yêu cầu trả hàng hoàn tiền cho đơn hàng này không?')) {
                return;
            }

            const submitBtn = refundForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            const formData = new FormData(refundForm);
            
            fetch(refundForm.action, {
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
                    alert(res.message || 'Có lỗi xảy ra khi yêu cầu hoàn tiền!');
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Lỗi kết nối hệ thống!');
                submitBtn.disabled = false;
            });
        });
    }
});
</script>
@endsection
