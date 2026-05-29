@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="fw-bold">{{ __('messages.manage_categories') }}</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle me-1"></i> {{ __('messages.add_category') }}
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('messages.id') }}</th>
                        <th>{{ __('messages.category_name') }}</th>
                        <th>{{ __('messages.created_at') }}</th>
                        <th class="text-end pe-4">{{ __('messages.product_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td class="ps-4">{{ $cat->id }}</td>
                        <td class="fw-bold">{{ $cat->name }}</td>
                        <td>{{ date('d/m/Y', strtotime($cat->created_at)) }}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $cat->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="{{ route('category.delete', $cat->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('messages.delete_category_confirm') }}')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('category.update', $cat->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="original_updated_at" value="{{ $cat->updated_at }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('messages.edit_category') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">{{ __('messages.category_name') }}</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $cat->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('category.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.add_category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">{{ __('messages.category_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('messages.category_placeholder') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('messages.add_category') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection