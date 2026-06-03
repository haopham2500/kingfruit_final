@extends('layouts.admin')

@section('content')
<style>
    /* ÉP BẢNG KHÍT VÀ HIỆN THANH CUỘN */
    .table-responsive {
        width: 100%;
        overflow-x: auto !important;
        display: block;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eee;
    }

    .table-custom {
        min-width: 1000px !important;
        table-layout: fixed;
    }

    .table-custom th, .table-custom td {
        padding: 12px 16px !important;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .col-id { width: 80px; }
    .col-user { width: 180px; }
    .col-reason { width: 340px; }
    .col-amount { width: 120px; }
    .col-action { width: 280px; }

    /* Thanh cuộn màu xanh King Fruit */
    .table-responsive::-webkit-scrollbar { height: 8px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #198754; border-radius: 10px; }
</style>

<div class="main-content p-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold m-0"><i class="bi bi-arrow-counterclockwise me-2 text-success"></i>Duyệt Trả hàng / Hoàn tiền</h4>
        <div class="badge bg-body-tertiary text-body shadow-sm p-2 border fw-bold">
            Đang chờ: {{ $orders->where('status', 'wait_refund')->count() }} yêu cầu
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center col-id">Mã ĐH</th>
                        <th class="col-user">Khách hàng</th>
                        <th class="col-reason">Nội dung yêu cầu</th>
                        <th class="text-end col-amount">Số tiền</th>
                        <th class="col-action">Xử lý yêu cầu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-dark px-2 py-1" style="font-size: 0.8rem;">#{{ $order->id }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $order->receiver_name ?? ($order->user->name ?? 'Khách vãng lai') }}</div>
                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                {{ $order->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="text-wrap" style="word-break: break-word; font-weight: 500;">
                                {{ $order->refund_reason }}
                            </div>
                            @if($order->refund_evidence)
                                <div class="mt-2">
                                    <a href="{{ asset('refunds/' . $order->refund_evidence) }}" target="_blank" class="text-decoration-none small text-primary fw-bold">
                                        <i class="bi bi-image me-1"></i> Xem minh chứng
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="text-danger fw-bold text-end" style="font-size: 0.95rem;">
                            {{ number_format($order->total_amount, 0, ',', '.') }}đ
                        </td>
                        <td>
                            @if($order->status === 'wait_refund')
                                <form action="{{ route('admin.refunds.process', $order->id) }}" method="POST" class="p-2 border rounded bg-light shadow-sm refund-process-form">
                                    @csrf
                                    <input type="hidden" name="action" class="action-input" value="">
                                    <div class="mb-2">
                                        <textarea name="feedback" class="form-control form-control-sm" rows="2" placeholder="Phản hồi cho khách..." style="font-size: 0.8rem;"></textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-success btn-sm flex-fill fw-bold py-1 btn-approve">
                                            Duyệt
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm flex-fill fw-bold py-1 btn-reject">
                                            Từ chối
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="p-2 border rounded bg-light text-center">
                                    @if($order->status === 'refunded')
                                        <span class="badge bg-success mb-1">Đã duyệt hoàn tiền</span>
                                    @else
                                        <span class="badge bg-danger mb-1">Từ chối hoàn tiền</span>
                                    @endif
                                    @if($order->refund_feedback)
                                        <div class="small text-muted text-start mt-1" style="font-size: 0.75rem; border-top: 1px dashed #ccc; padding-top: 4px;">
                                            <strong>Phản hồi:</strong> {{ $order->refund_feedback }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Không có yêu cầu hoàn tiền nào!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.refund-process-form').forEach(form => {
        const actionInput = form.querySelector('.action-input');
        
        form.querySelector('.btn-approve').addEventListener('click', function() {
            actionInput.value = 'approve';
            form.submit();
        });
        
        form.querySelector('.btn-reject').addEventListener('click', function() {
            actionInput.value = 'reject';
            form.submit();
        });
    });
});
</script>
@endsection
