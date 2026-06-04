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

    .error-text {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }
</style>

<div class="container py-5" style="max-width: 500px;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h2 class="text-center fw-bold text-success mb-4">{{ __('messages.register_title') }}</h2>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-custom alert-danger-custom alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Lỗi đăng ký:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.full_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') form-error @enderror" placeholder="{{ __('messages.full_name_placeholder') }}" value="{{ old('name') }}" required maxlength="30">
                    @error('name')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') form-error @enderror" placeholder="example@gmail.com" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="{{ __('messages.email_domain_hint') }}" value="{{ old('email') }}" required>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle"></i> {{ __('messages.email_domain_hint') }}
                    </small>
                    @error('email')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('messages.phone_number') }}</label>
                    <input type="text" name="phone" class="form-control @error('phone') form-error @enderror" placeholder="{{ __('messages.phone_placeholder') }}" value="{{ old('phone') }}" required inputmode="numeric" pattern="[0-9]*" maxlength="11">
                    @error('phone')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">{{ __('messages.password') }}</label>
                    <input type="password" name="password" class="form-control @error('password') form-error @enderror" placeholder="{{ __('messages.password_hint') }}" required>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-lock"></i> {{ __('messages.password_hint') }}
                    </small>
                    @error('password')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-2" style="border-radius: 50px; font-size: 16px;">
                    <i class="bi bi-check-circle me-2"></i>{{ __('messages.register_now') }}
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">{{ __('messages.already_have_account') }} <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">{{ __('messages.login_here') }}</a></p>
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