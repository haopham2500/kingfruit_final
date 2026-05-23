@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm p-4 border-0" style="border-radius: 15px; max-width: 600px; margin: auto;">
        <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('messages.edit_voucher') }}</h4>
        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="original_updated_at" value="{{ $voucher->updated_at }}">
            
            <div class="mb-3">
                <label class="form-label fw-bold">{{ __('messages.voucher_code') }}</label>
                <input type="text" name="code" class="form-control" value="{{ $voucher->code }}" required>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold">{{ __('messages.voucher_type') }}</label>
                    <select name="type" class="form-select">
                        <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>{{ __('messages.voucher_cash') }}</option>
                        <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>{{ __('messages.voucher_percent') }}</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">{{ __('messages.voucher_value') }}</label>
                    <input type="number" name="discount_value" class="form-control" value="{{ $voucher->discount_value }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">{{ __('messages.voucher_min_order') }}</label>
                <input type="number" name="min_order_value" class="form-control" value="{{ $voucher->min_order_value }}">
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="{{ $voucher->quantity }}">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">{{ __('messages.voucher_expiry') }}</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ $voucher->expiry_date }}">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">{{ __('messages.update_voucher') }}</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light w-100 fw-bold py-2">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection