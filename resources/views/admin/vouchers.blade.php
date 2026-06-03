@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">{{ __('messages.manage_vouchers') }}</h2>
        <div class="badge bg-body-tertiary text-body p-2 border shadow-sm rounded-pill px-3">
            <i class="bi bi-calendar3 me-2 text-success"></i>{{ date('d/m/Y') }}
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card p-4 mb-4 shadow-sm border-0" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4 text-success"><i class="bi bi-plus-circle-fill me-2"></i>{{ __('messages.create_voucher') }}</h5>
                <form action="{{ route('admin.vouchers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('messages.voucher_code') }}</label>
                        <input type="text" name="code" class="form-control form-control-lg @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="{{ __('messages.voucher_code_example') }}" required style="text-transform: uppercase;">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('messages.voucher_type') }}</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>{{ __('messages.voucher_cash') }}</option>
                                <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>{{ __('messages.voucher_percent') }}</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('messages.voucher_value') }}</label>
                            <input type="number" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value') }}" required>
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('messages.voucher_min_order') }}</label>
                        <input type="number" name="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror" value="{{ old('min_order_value', '0') }}">
                        @error('min_order_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('messages.voucher_quantity') }}</label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', '100') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('messages.voucher_expiry') }}</label>
                            <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" required value="{{ old('expiry_date', date('Y-m-d', strtotime('+1 month'))) }}">
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold py-3 mt-2 shadow-sm">
                        <i class="bi bi-lightning-fill me-2"></i>{{ __('messages.create_voucher_button') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card p-4 shadow-sm border-0" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4"><i class="bi bi-list-stars me-2"></i>{{ __('messages.active_vouchers') }}</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>{{ __('messages.coupon_code') }}</th>
                                <th>{{ __('messages.coupon_discount') }}</th>
                                <th>{{ __('messages.coupon_stock') }}</th>
                                <th>{{ __('messages.coupon_expires') }}</th>
                                <th class="text-center">{{ __('messages.coupon_actions') }}</th>
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
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ __('messages.order_above') }} {{ number_format($v->min_order_value) }}đ</div>
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

        <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_voucher_confirm') }}')">
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
                            <tr><td colspan="5" class="text-center text-muted py-5">{{ __('messages.no_vouchers_yet') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection