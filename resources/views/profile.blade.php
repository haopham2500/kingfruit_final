@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('messages.profile_title') }}</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ __('messages.user_info') }}</h5>
            <p><strong>{{ __('messages.name') }}:</strong> {{ $user->name }}</p>
            <p><strong>{{ __('messages.email') }}:</strong> {{ $user->email }}</p>
            <p><strong>{{ __('messages.phone_number') }}:</strong> {{ $user->phone }}</p>
        </div>
    </div>

    <div class="d-flex gap-3 mb-4">
        <a href="#order-history" class="btn btn-outline-success flex-fill py-3 fs-5">
            <i class="bi bi-clock-history me-2"></i> {{ __('messages.purchase_history') }}
        </a>
        <a href="{{ route('orders.track') }}" class="btn btn-outline-primary flex-fill py-3 fs-5">
            <i class="bi bi-truck me-2"></i> {{ __('messages.track_orders') }}
        </a>
    </div>

    <div id="order-history" class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">{{ __('messages.purchase_history') }}</h5>
        </div>
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="alert alert-light text-center mb-0">{{ __('messages.no_orders') }}</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.order_id') }}</th>
                                <th>{{ __('messages.order_date') }}</th>
                                <th>{{ __('messages.order_total') }}</th>
                                <th>{{ __('messages.order_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                @php
                                    $statusKey = 'order_' . str_replace('-', '_', $order->status);
                                @endphp
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ __($statusKey) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection