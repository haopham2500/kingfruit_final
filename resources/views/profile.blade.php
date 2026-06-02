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
        <a href="{{ route('orders.track') }}" class="btn btn-outline-primary flex-fill py-3 fs-5">
            <i class="bi bi-truck me-2"></i> {{ __('messages.track_orders') }}
        </a>
        <button type="button" class="btn btn-outline-warning flex-fill py-3 fs-5" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
            <i class="bi bi-key me-2"></i> {{ __('messages.change_password') }}
        </button>
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

                                    $statusColors = [
                                        'pending'         => 'bg-warning text-dark',
                                        'processing'      => 'bg-info text-white',
                                        'completed'       => 'bg-success text-white',
                                        'cancelled'       => 'bg-danger text-white',
                                        'refunded'        => 'bg-primary text-white',
                                        'wait_refund'     => 'bg-dark text-white',
                                        'refund_rejected' => 'bg-danger text-white',
                                    ];
                                    $badgeClass = $statusColors[$order->status] ?? 'bg-secondary text-white';
                                @endphp
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }}">{{ __('messages.' . $statusKey) }}</span>
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

<!-- Modal Đổi Mật Khẩu -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-warning text-dark border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="changePasswordModalLabel">
                    <i class="bi bi-key-fill me-2"></i>{{ __('messages.change_password') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="change-password-form" action="{{ route('profile.changePassword') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div id="change-password-alert" class="alert d-none"></div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.current_password') }}</label>
                        <input type="password" name="current_password" class="form-control" required placeholder="********">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.new_password') }}</label>
                        <input type="password" name="new_password" class="form-control" required placeholder="********">
                        <div class="form-text small text-muted">{{ __('messages.password_hint') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.confirm_password') }}</label>
                        <input type="password" name="confirm_password" class="form-control" required placeholder="********">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">{{ __('messages.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#change-password-form').submit(function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const alertBox = $('#change-password-alert');
        
        alertBox.addClass('d-none').removeClass('alert-success alert-danger');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                if (res.success) {
                    alertBox.addClass('alert-success').removeClass('d-none').text(res.message);
                    form[0].reset();
                    
                    // Đóng modal sau 1.5s
                    setTimeout(function() {
                        $('#changePasswordModal').modal('hide');
                        alertBox.addClass('d-none');
                        location.reload();
                    }, 1500);
                } else {
                    alertBox.addClass('alert-danger').removeClass('d-none').text(res.message || 'Lỗi đổi mật khẩu!');
                    submitBtn.prop('disabled', false);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Lỗi kết nối hệ thống!';
                if (xhr.status === 422) {
                    // Lấy lỗi validation của Laravel
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alertBox.addClass('alert-danger').removeClass('d-none').html(errorMsg);
                submitBtn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection