@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4 border-0" style="border-radius: 15px; max-width: 600px; margin: auto;">
        <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2 text-primary"></i>Chỉnh sửa Voucher</h4>
        
        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Mã Code</label>
                <input type="text" name="code" class="form-control" value="{{ $voucher->code }}" required>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold">Loại giảm</label>
                    <select name="type" class="form-select">
                        <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Tiền mặt (đ)</option>
                        <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Giá trị giảm</label>
                    <input type="number" name="discount_value" class="form-control" value="{{ $voucher->discount_value }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Đơn hàng tối thiểu (đ)</label>
                <input type="number" name="min_order_value" class="form-control" value="{{ $voucher->min_order_value }}">
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="{{ $voucher->quantity }}">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Ngày hết hạn</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ $voucher->expiry_date }}">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">CẬP NHẬT</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light w-100 fw-bold py-2">HỦY</a>
            </div>
        </form>
    </div>
</div>
@endsection