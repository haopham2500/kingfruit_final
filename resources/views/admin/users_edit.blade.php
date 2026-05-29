@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4 fw-bold">{{ __('messages.edit_user_title') }}</h2>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="original_updated_at" value="{{ $user->updated_at }}">
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.phone_number') }}</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.role') }}</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>{{ __('messages.customer_role') }}</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('messages.admin_role') }}</option>
                        <option value="banned" {{ old('role', $user->role) == 'banned' ? 'selected' : '' }}>{{ __('messages.banned_role') }}</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </form>
        </div>
    </div>
</div>
@endsection