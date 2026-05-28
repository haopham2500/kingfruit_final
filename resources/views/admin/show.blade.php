@extends('layouts.admin')

@section('content')
<style>
    .card { border: none; border-radius: 12px; }
    .img-product { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; }
    .info-label { color: #6c757d; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-weight: 600; color: #212529; margin-bottom: 15px; font-size: 0.9rem; }
    .table thead { background-color: #f8f9fa; border-top: 1px solid #eee; }
    .table th { font-size: 0.75rem; text-transform: uppercase; color: #6c757d; }
</style>

<div class="main-content p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold m-0 text-body">
                <i class="bi bi-receipt me-2 text-success"></i>{{ __('messages.order_detail') }} #{{ $order->id }}
            </h4>
            <span class="text-muted small">{{ __('messages.order_date') }}: {{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-4 mb-3">
                <h6 class="fw-bold mb-3 border-bottom pb-2">{{ __('messages.order_shipping_info') }}</h6>
                
                <div class="info-label">{{ __('messages.receiver_name') }}</div>
                <div class="info-value">{{ $order->receiver_name ?? ($order->user->name ?? __('messages.guest_customer')) }}</div>

                <div class="info-label">{{ __('messages.phone_number') }}</div>
                <div class="info-value text-primary">{{ $order->phone ?? '0911901782' }}</div>

                <div class="info-label">{{ __('messages.order_shipping_address') }}</div>
                <div class="info-value" style="line-height: 1.4;">{{ $order->address }}</div>

                <hr class="text-muted opacity-25">

                <div class="info-label">{{ __('messages.order_status') }}</div>
                <div class="mt-2">
                    @php
                        $status_map = [
                            'pending'    => ['c' => 'bg-warning text-dark', 't' => __('messages.order_pending')],
                            'processing' => ['c' => 'bg-info text-white', 't' => __('messages.order_processing')],
                            'completed'  => ['c' => 'bg-success', 't' => __('messages.order_completed')],
                            'cancelled'  => ['c' => 'bg-danger', 't' => __('messages.order_cancelled')],
                        ];
                        $st = $status_map[$order->status] ?? ['c' => 'bg-secondary', 't' => $order->status];
                    @endphp
                    <span class="badge {{ $st['c'] }} px-3 py-2 rounded-pill shadow-sm">
                        {{ $st['t'] }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body p-0">
                    <div class="p-3">
                        <h6 class="fw-bold m-0">{{ __('messages.order_summary') }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">{{ __('messages.product') }}</th>
                                    <th></th>
                                    <th class="text-center">{{ __('messages.quantity') }}</th>
                                    <th class="text-end">{{ __('messages.product_price') }}</th>
                                    <th class="text-end pe-3">{{ __('messages.order_total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Ní lưu ý: orderDetails là tên function relationship trong Model Order --}}
                                @foreach($order->details as $item)
                                <tr>
                                    <td class="ps-3" style="width: 80px;">
                                        <img src="{{ asset('storage/' . ($item->product->image ?? 'default.jpg')) }}" 
                                             class="img-product border"
                                             onerror="this.src='https://via.placeholder.com/100x100?text=Fruit'">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-body">{{ $item->product->name ?? __('messages.product_deleted') }}</div>
                                        <div class="text-muted small">{{ __('messages.product_id') }}: #{{ $item->product_id }}</div>
                                    </td>
                                    <td class="text-center fw-bold">x{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price) }}đ</td>
                                    <td class="text-end pe-3 fw-bold text-success">
                                        {{ number_format($item->quantity * $item->price) }}đ
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end py-3 fw-bold">TỔNG CỘNG:</td>
                                    <td class="text-end pe-3 py-3 fw-bold text-danger fs-5">
                                        {{ number_format($order->total_amount) }}đ
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->note)
            <div class="card shadow-sm p-3">
                <h6 class="fw-bold mb-2"><i class="bi bi-pencil-square me-1"></i> {{ __('messages.order_notes') }}</h6>
                <div class="text-muted small">{{ $order->note }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection