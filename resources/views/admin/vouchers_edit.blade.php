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
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $voucher->code) }}" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold">{{ __('messages.voucher_type') }}</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>{{ __('messages.voucher_cash') }}</option>
                        <option value="percent" {{ old('type', $voucher->type) == 'percent' ? 'selected' : '' }}>{{ __('messages.voucher_percent') }}</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">{{ __('messages.voucher_value') }}</label>
                    <input type="number" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', $voucher->discount_value) }}" required>
                    @error('discount_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">{{ __('messages.voucher_min_order') }}</label>
                <input type="number" name="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror" value="{{ old('min_order_value', $voucher->min_order_value) }}">
                @error('min_order_value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $voucher->quantity) }}">
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">{{ __('messages.voucher_expiry') }}</label>
                    <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date', $voucher->expiry_date) }}">
                    @error('expiry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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