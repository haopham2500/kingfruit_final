@extends('layouts.admin')

@section('content')
<style>
    /* 1. ÉP BẢNG KHÍT VÀ HIỆN THANH CUỘN */
    .table-responsive {
        width: 100%;
        overflow-x: auto !important; /* Hiện thanh cuộn khi lòi */
        display: block;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eee;
    }

    .table-custom {
        min-width: 1100px !important; /* Ép bảng rộng để hiện thanh cuộn */
        table-layout: fixed;
    }

    .table-custom th, .table-custom td {
        padding: 6px 8px !important; /* Giảm padding tối đa */
        vertical-align: middle;
        font-size: 0.8rem;
    }

    /* 2. CỐ ĐỊNH ĐỘ RỘNG CỘT */
    .col-id { width: 10px; }
    .col-user { width: 50px; }
    .col-total { width: 30px; }
    .col-date { width: 30px; }
    .col-stt { width: 30px; }
    .col-opt { width: 150px; }

    /* Thanh cuộn màu xanh King Fruit */
    .table-responsive::-webkit-scrollbar { height: 8px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #198754; border-radius: 10px; }

    /* 3. TOAST THÔNG BÁO GỌN GÀNG */
    .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
</style>

<div class="main-content p-3">
    <div class="toast-container">
        @if(session('success'))
        <div class="toast show align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold m-0"><i class="bi bi-cart-check me-2 text-success"></i>Danh sách đơn hàng</h4>
        <div class="badge bg-white text-dark shadow-sm p-2 border">
            Hệ thống có: <span class="fw-bold text-success">{{ $orders->count() }}</span> đơn hàng
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center col-id">Mã đơn</th>
                        <th class="col-user">Khách hàng / Liên hệ</th>
                        <th class="text-end col-total">Tổng tiền</th>
                        <th class="text-center col-date">Ngày đặt</th>
                        <th class="text-center col-stt">Trạng thái</th>
                        <th class="text-center col-opt">Xử lý</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-dark">#{{ $order->id }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $order->receiver_name ?? ($order->user->name ?? 'Khách lẻ') }}</div>
                            <div class="text-primary small" style="font-size: 0.75rem;">
                                <i class="bi bi-telephone me-1"></i>0911901782
                            </div>
                            <div class="text-muted text-truncate small" style="max-width: 200px; font-size: 0.7rem;">
                                <i class="bi bi-geo-alt me-1"></i>{{ $order->address }}
                            </div>
                        </td>
                        <td class="text-danger fw-bold text-end">{{ number_format($order->total_amount) }}đ</td>
                        <td class="text-center small text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @php
                                $status_map = [
                                    'pending'    => ['c' => 'bg-warning text-dark', 't' => 'Chờ duyệt'],
                                    'processing' => ['c' => 'bg-info text-white', 't' => 'Đang giao'],
                                    'completed'  => ['c' => 'bg-success', 't' => 'Đã giao'],
                                    'cancelled'  => ['c' => 'bg-danger', 't' => 'Hủy đơn'],
                                    'refunded'   => ['c' => 'bg-primary', 't' => 'Hoàn tiền'],
                                    'wait_refund'=> ['c' => 'bg-dark', 't' => 'Chờ hoàn'],
                                ];
                                $st = $status_map[$order->status] ?? ['c' => 'bg-secondary', 't' => $order->status];
                            @endphp
                            <span class="badge {{ $st['c'] }} px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                {{ $st['t'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-info btn-sm py-0 px-2 shadow-sm" title="Xem chi tiết" style="font-size: 0.7rem;">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <div class="dropdown">
                                    <button class="btn btn-outline-success btn-sm py-0 px-2 shadow-sm" type="button" data-bs-toggle="dropdown" style="font-size: 0.7rem;">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="font-size: 0.75rem;">
                                        <li><h6 class="dropdown-header">Đổi trạng thái</h6></li>
                                        @foreach(['pending' => 'Chờ duyệt', 'processing' => 'Đang giao', 'completed' => 'Đã giao', 'cancelled' => 'Hủy đơn'] as $key => $label)
                                        <li>
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ $key }}">
                                                <button type="submit" class="dropdown-item {{ $order->status == $key ? 'active' : '' }}">
                                                    {{ $label }}
                                                </button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Chưa có đơn hàng nào!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Tự động tắt thông báo sau 3 giây
    document.addEventListener('DOMContentLoaded', function () {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    });
</script>
@endsection