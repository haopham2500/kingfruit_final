@extends('layouts.master')

@section('content')
<style>
    .alert-custom {
        border-radius: 15px;
        border: none;
        padding: 15px 20px;
        animation: slideDownAlert 0.4s ease-out;
    }

    @keyframes slideDownAlert {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success-custom {
        background: linear-gradient(135deg, #198754 0%, #157347 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, #dc3545 0%, #bb2d3b 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .form-error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 5px rgba(220, 53, 69, 0.3) !important;
    }
</style>

<div class="container py-5" style="max-width: 450px;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-success">{{ __('messages.login_title') }}</h2>
                <p class="text-muted">{{ __('messages.login_welcome') }}</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-custom alert-success-custom alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2" style="font-size: 20px;"></i>
                    <div>
                        <strong>Thành công!</strong>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="alert alert-custom alert-danger-custom alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2" style="font-size: 20px;"></i>
                    <div>
                        <strong>Lỗi!</strong>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control" placeholder="{{ __('messages.email_placeholder') }}" value="{{ old('email') }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">{{ __('messages.password') }}</label>
                    <input type="password" name="password" class="form-control" placeholder="{{ __('messages.password_placeholder') }}" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2" style="border-radius: 50px; font-size: 16px;">
                    <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('messages.login') }}
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="small">{{ __('messages.no_account') ?? 'Chưa có tài khoản?' }} <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">{{ __('messages.register') }}</a></p>
                <a href="{{ route('home') }}" class="text-muted small text-decoration-none"><i class="bi bi-house-door"></i> {{ __('messages.back_to_home') }}</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-hide alert after 5 seconds
    document.addEventListener('DOMContentLoaded', () => {
        const alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endsection