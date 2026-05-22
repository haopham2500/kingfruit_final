@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Quản lý Khuyến mãi</h2>
        <div class="badge bg-body-tertiary text-body p-2 border shadow-sm rounded-pill px-3">
            <i class="bi bi-calendar3 me-2 text-success"></i>{{ date('d/m/Y') }}
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card p-4 mb-4 shadow-sm border-0" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4 text-success"><i class="bi bi-plus-circle-fill me-2"></i>Tạo mã mới</h5>
                <form action="{{ route('admin.vouchers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mã Voucher</label>
                        <input type="text" name="code" class="form-control form-control-lg" placeholder="Vd: KING50K" required style="text-transform: uppercase;">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Loại giảm</label>
                            <select name="type" class="form-select">
                                <option value="fixed">Tiền mặt (đ)</option>
                                <option value="percent">Phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Giá trị</label>
                            <input type="number" name="discount_value" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Đơn tối thiểu (VNĐ)</label>
                        <input type="number" name="min_order_value" class="form-control" value="0">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Số lượng</label>
                            <input type="number" name="quantity" class="form-control" value="100" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Hết hạn</label>
                            <input type="date" name="expiry_date" class="form-control" required value="{{ date('Y-m-d', strtotime('+1 month')) }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold py-3 mt-2 shadow-sm">
                        <i class="bi bi-lightning-fill me-2"></i>TẠO VOUCHER
                    </button>
                </form>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card p-4 shadow-sm border-0" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4"><i class="bi bi-list-stars me-2"></i>Danh sách mã đang chạy</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>MÃ CODE</th>
                                <th>MỨC GIẢM</th>
                                <th>SỐ LƯỢNG</th>
                                <th>HẾT HẠN</th>
                                <th class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $v)
                            <tr>
                                <td>
                                    <span class="badge bg-body-tertiary text-success border border-success px-3 py-2">
                                        {{ $v->code }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-body">
                                        {{ number_format($v->discount_value) }}{{ $v->type == 'percent' ? '%' : 'đ' }}
                                    </span>
                                    <div class="text-muted" style="font-size: 0.7rem;">Đơn > {{ number_format($v->min_order_value) }}đ</div>
                                </td>
                                <td><i class="bi bi-ticket-detailed me-1 text-secondary"></i>{{ $v->quantity }}</td>
                                <td>
                                    <span class="small {{ strtotime($v->expiry_date) < time() ? 'text-danger fw-bold' : '' }}">
                                        {{ date('d/m/Y', strtotime($v->expiry_date)) }}
                                    </span>
                                </td>
                                <td class="text-center">
    <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('admin.vouchers.edit', $v->id) }}" class="btn btn-sm btn-outline-primary border-0">
            <i class="bi bi-pencil-fill fs-5"></i>
        </a>

        <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Xóa hả ní?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger border-0">
                <i class="bi bi-trash3-fill fs-5"></i>
            </button>
        </form>
    </div>
</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Chưa có mã nào. Hãy tạo mã đầu tiên!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection