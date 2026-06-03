@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-success">{{ __('messages.manage_products') }}</h2>
    <div>
        <button id="btnDeleteSelected" class="btn btn-danger rounded-pill px-4 me-2 d-none" onclick="deleteSelected()">
            <i class="bi bi-trash me-2"></i>Xóa đã chọn
        </button>
        <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add_product') }}
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" style="width: 40px;">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                    </th>
                    <th>{{ __('messages.product_id') }}</th>
                    <th>{{ __('messages.product_image') }}</th>
                    <th>{{ __('messages.product_name') }}</th>
                    <th>{{ __('messages.product_price') }}</th>
                    <th>{{ __('messages.product_unit') }}</th>
                    <th class="text-end pe-4">{{ __('messages.product_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $pro)
                <tr>
                    <td class="ps-4">
                        <input class="form-check-input product-checkbox" type="checkbox" value="{{ $pro->id }}">
                    </td>
                    <td>{{ $pro->id }}</td>
                    <td><img src="{{ asset('images/' . $pro->image) }}" width="50" class="rounded border"></td>
                    <td class="fw-bold">{{ $pro->name }}</td>
                    <td class="text-danger fw-bold">{{ number_format($pro->price) }}đ</td>
                    <td>{{ $pro->unit ?? __('messages.not_available') }}</td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-primary me-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEdit{{ $pro->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form action="{{ route('product.delete', $pro->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ní chắc chưa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="modalEdit{{ $pro->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow">
                            <form action="{{ route('product.update', $pro->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="original_updated_at" value="{{ $pro->updated_at }}">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">{{ __('messages.edit_product') }}: {{ $pro->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold">{{ __('messages.product_name') }}</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $pro->name }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">{{ __('messages.category') }}</label>
                                            <select name="category_id" class="form-select">
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $pro->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">{{ __('messages.product_price') }}</label>
                                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ $pro->price }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">{{ __('messages.product_unit') }}</label>
                                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ $pro->unit }}" required>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold d-block">{{ __('messages.current_image') }}</label>
                                        <img src="{{ asset('images/' . $pro->image) }}" width="80" class="mb-2 border rounded">
                                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('messages.product_description') }}</label>
                                        <textarea name="description" class="form-control" rows="3">{{ $pro->description }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary px-4">{{ __('messages.save_changes') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">{{ __('messages.add_product') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">{{ __('messages.product_name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('messages.product_name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.category') }}</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.product_price') }}</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.product_unit') }}</label>
                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" placeholder="{{ __('messages.product_unit') }}" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.product_image') }}</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.product_description') }}</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-success px-4">{{ __('messages.add_product') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteMultipleForm" action="{{ route('product.deleteMultiple') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="ids" id="deleteIds">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const btnDeleteSelected = document.getElementById('btnDeleteSelected');
        const deleteIdsInput = document.getElementById('deleteIds');
        const deleteMultipleForm = document.getElementById('deleteMultipleForm');

        function updateDeleteButton() {
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
            if (checkedCount > 0) {
                btnDeleteSelected.classList.remove('d-none');
            } else {
                btnDeleteSelected.classList.add('d-none');
            }
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateDeleteButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.product-checkbox:checked').length === checkboxes.length;
                selectAll.checked = allChecked;
                updateDeleteButton();
            });
        });

        window.deleteSelected = function() {
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            if (checkedBoxes.length > 0) {
                if (confirm('Bạn có chắc muốn xóa hay không?')) {
                    const ids = Array.from(checkedBoxes).map(cb => cb.value).join(',');
                    deleteIdsInput.value = ids;
                    deleteMultipleForm.submit();
                }
            }
        };
    });
</script>
@endsection