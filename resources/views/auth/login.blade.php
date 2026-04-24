@extends('layouts.master')

@section('content')
<div class="container py-5" style="max-width: 450px;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-success">ĐĂNG NHẬP</h2>
                <p class="text-muted">Chào mừng bạn quay trở lại!</p>
            </div>

            @if(session('error'))
            <div class="alert alert-danger text-center py-2">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@kingfruit.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="********" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2">Đăng Nhập</button>
            </form>

            <div class="text-center mt-4">
                <p class="small">Chưa có tài khoản? <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">Đăng ký </a></p>
                <a href="{{ route('home') }}" class="text-muted small text-decoration-none"><i class="bi bi-house-door"></i> Quay lại trang chủ</a>
            </div>
        </div>
    </div>
</div>
@endsection