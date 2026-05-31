@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-content p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0"><i class="bi bi-speedometer2 me-2 text-success"></i>{{ __('messages.dashboard_overview') }}</h4>
    </div>

    <!-- 4 Ô thống kê -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-1 opacity-75">{{ __('messages.revenue') }}</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($totalRevenue, 0, ',', '.') }} đ</h3>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-1 opacity-75">{{ __('messages.products') }}</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h3>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-1 opacity-75">{{ __('messages.categories') }}</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($totalCategories) }}</h3>
                    </div>
                    <i class="bi bi-tags fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-1 opacity-75">{{ __('messages.customers') }}</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($totalCustomers) }}</h3>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Biểu đồ -->
    <div class="row g-3 mb-4">
        <!-- Biểu đồ 1: Số đơn hàng -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3 pb-0">
                    <h6 class="fw-bold"><i class="bi bi-bar-chart-line me-2"></i>{{ __('messages.orders_last_30_days') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Biểu đồ 2: Số người đăng ký -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3 pb-0">
                    <h6 class="fw-bold"><i class="bi bi-person-lines-fill me-2"></i>{{ __('messages.new_users_last_30_days') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="usersChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 5 Đơn hàng mới nhất -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom pt-3 pb-2">
            <h6 class="fw-bold m-0"><i class="bi bi-clock-history me-2 text-primary"></i>{{ __('messages.latest_5_orders') }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">{{ __('messages.id') }}</th>
                        <th>{{ __('messages.customer_contact') }}</th>
                        <th class="text-end">{{ __('messages.order_total') }}</th>
                        <th class="text-center">{{ __('messages.order_date') }}</th>
                        <th class="text-center">{{ __('messages.order_status') }}</th>
                        <th class="text-center">{{ __('messages.product_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestOrders as $order)
                    <tr>
                        <td class="text-center"><span class="badge bg-dark">#{{ $order->id }}</span></td>
                        <td>
                            <div class="fw-bold">{{ $order->receiver_name ?? ($order->user->name ?? __('messages.guest_customer')) }}</div>
                            <div class="text-muted small">{{ $order->phone_number ?? '' }}</div>
                        </td>
                        <td class="text-end text-danger fw-bold">{{ number_format($order->total_amount) }}đ</td>
                        <td class="text-center small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @php
                                $status_map = [
                                    'pending'    => ['c' => 'bg-warning text-dark', 't' => __('messages.order_pending')],
                                    'processing' => ['c' => 'bg-info text-white', 't' => __('messages.order_processing')],
                                    'completed'  => ['c' => 'bg-success', 't' => __('messages.order_completed')],
                                    'cancelled'  => ['c' => 'bg-danger', 't' => __('messages.order_cancelled')],
                                    'refunded'   => ['c' => 'bg-primary', 't' => __('messages.order_refunded')],
                                    'wait_refund'=> ['c' => 'bg-dark', 't' => __('messages.order_wait_refund')],
                                    'returning'  => ['c' => 'bg-secondary', 't' => __('messages.order_returning')],
                                ];
                                $st = $status_map[$order->status] ?? ['c' => 'bg-secondary', 't' => $order->status];
                            @endphp
                            <span class="badge {{ $st['c'] }}">{{ $st['t'] }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-info btn-sm" title="{{ __('messages.view_details') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">{{ __('messages.no_orders') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderDates = @json($orderDates);
        const orderCounts = @json($orderCounts);
        
        const userDates = @json($userDates);
        const userCounts = @json($userCounts);

        // Biểu đồ đơn hàng
        const ctxOrders = document.getElementById('ordersChart').getContext('2d');
        new Chart(ctxOrders, {
            type: 'line',
            data: {
                labels: orderDates,
                datasets: [{
                    label: '{{ __('messages.order_count_label') }}',
                    data: orderCounts,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Biểu đồ người dùng
        const ctxUsers = document.getElementById('usersChart').getContext('2d');
        new Chart(ctxUsers, {
            type: 'bar',
            data: {
                labels: userDates,
                datasets: [{
                    label: '{{ __('messages.new_user_label') }}',
                    data: userCounts,
                    backgroundColor: '#ffc107',
                    borderColor: '#ffc107',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endsection
