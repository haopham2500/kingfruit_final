@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Thông tin cá nhân</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Thông tin người dùng</h5>
            <p><strong>Họ tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $user->phone }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Lịch sử đơn hàng đã mua</h5>
            <ul>
                @foreach($orders as $order)
                    @if($order->status === 'completed')
                        <li>Đơn hàng #{{ $order->id }} - Tổng tiền: {{ number_format($order->total_amount) }}đ - Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Theo dõi đơn hàng đã đặt</h5>
            <ul>
                @foreach($orders as $order)
                    @if($order->status !== 'completed')
                        <li>Đơn hàng #{{ $order->id }} - Tổng tiền: {{ number_format($order->total_amount) }}đ - Trạng thái: {{ $order->status }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection