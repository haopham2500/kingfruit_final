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
            <h2 class="text-center fw-bold text-success mb-4">ĐĂNG KÝ TÀI KHOẢN</h2>

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
                    <label class="form-label fw-bold">Họ Tên</label>
                    <input type="text" name="name" class="form-control @error('name') form-error @enderror" placeholder="Nhập họ tên" value="{{ old('name') }}" required>
                    @error('name')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') form-error @enderror" placeholder="example@gmail.com" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Vui lòng sử dụng địa chỉ Gmail chính thức (@gmail.com) để đăng ký" value="{{ old('email') }}" required>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle"></i> Chỉ chấp nhận email @gmail.com
                    </small>
                    @error('email')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control @error('phone') form-error @enderror" placeholder="Nhập số điện thoại" value="{{ old('phone') }}" required>
                    @error('phone')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control @error('password') form-error @enderror" placeholder="Ít nhất 6 ký tự" required>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-lock"></i> Mật khẩu phải có ít nhất 6 ký tự
                    </small>
                    @error('password')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-2" style="border-radius: 50px; font-size: 16px;">
                    <i class="bi bi-check-circle me-2"></i>TẠO TÀI KHOẢN
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">Bạn đã có tài khoản? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Đăng nhập ngay tại đây</a></p>
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