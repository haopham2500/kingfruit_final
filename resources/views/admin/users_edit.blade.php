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
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.phone_number') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.role') }}</label>
                    <select name="role" class="form-select" {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>{{ __('messages.customer_role') }}</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>{{ __('messages.admin_role') }}</option>
                        <option value="banned" {{ $user->role == 'banned' ? 'selected' : '' }}>{{ __('messages.banned_role') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </form>
        </div>
    </div>
</div>
@endsection