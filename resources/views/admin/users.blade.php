@extends('layouts.admin')

@section('title', __('messages.user_management') . ' - KingFruit Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h2 class="fw-bold text-body">{{ __('messages.user_management') }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('crud') }}">{{ __('messages.admin_dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.user_management') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-body-tertiary py-3">
            <h5 class="mb-0 fw-bold">Danh sách thành viên</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4" style="width: 80px;">{{ __('messages.id') }}</th>
                            <th>{{ __('messages.customer_info') }}</th>
                            <th>{{ __('messages.phone_number') }}</th>
                            <th>{{ __('messages.status_role') }}</th>
                            <th>{{ __('messages.joined_at') }}</th>
                            <th class="text-end pe-4">{{ __('messages.product_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 text-muted">#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold me-3" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-body">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->phone ?? '---' }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">{{ __('messages.admin_role') }}</span>
                                @elseif($user->role === 'banned')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                        <i class="bi bi-patch-exclamation-fill me-1"></i>{{ __('messages.banned_role') }}
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">{{ __('messages.customer_role') }}</span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '---' }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-1">
                                    
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary border-0" title="{{ __('messages.edit_user') }}">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </a>

                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->role === 'banned')
                                                <button type="submit" class="btn btn-sm btn-outline-success border-0" title="{{ __('messages.unlock_account') }}">
                                                    <i class="bi bi-unlock-fill fs-5"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-outline-warning border-0" title="{{ __('messages.ban_account') }}">
                                                    <i class="bi bi-lock-fill fs-5"></i>
                                                </button>
                                            @endif
                                        </form>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="m-0" onsubmit="return confirm('{{ __('messages.ban_user_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="{{ __('messages.delete') }}">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small px-2">{{ __('messages.system') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-3"></i>
                                {{ __('messages.no_users_yet') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #6c757d;
        border-bottom: 1px solid #f0f2f5;
    }
    .table tbody td {
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid #f0f2f5;
    }
    .card { border-radius: 12px; }
    .badge { font-weight: 600; border-radius: 8px; }
    .btn-outline-primary:hover { background-color: #e7f1ff; color: #0d6efd; }
    .btn-outline-warning:hover { background-color: #fff3cd; color: #856404; }
    .btn-outline-success:hover { background-color: #d1e7dd; color: #0f5132; }
    .btn-outline-danger:hover { background-color: #f8d7da; color: #842029; }
</style>
@endsection