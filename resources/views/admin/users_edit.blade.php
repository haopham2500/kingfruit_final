@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4 fw-bold">Chỉnh sửa người dùng</h2>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Vai trò</label>
                    <select name="role" class="form-select" {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Khách hàng</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="banned" {{ $user->role == 'banned' ? 'selected' : '' }}>Khóa tài khoản</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection