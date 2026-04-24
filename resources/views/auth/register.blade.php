@extends('layouts.master')

@section('content')
<div class="container py-5" style="max-width: 500px;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h2 class="text-center fw-bold text-success mb-4">ĐĂNG KÝ</h2>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Họ Tên</label>
                    <input type="text" name="name" class="form-control" placeholder="Nhập họ tên" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="********" required>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-2">TẠO TÀI KHOẢN</button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">Bạn đã có tài khoản? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Đăng nhập ngay tại đây</a></p>
            </div>
        </div>
    </div>
</div>
@endsection