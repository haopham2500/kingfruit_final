@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-success">Quản lý sản phẩm</h2>
    <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Đơn vị</th>
                    <th class="text-end pe-4">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $pro)
                <tr>
                    <td class="ps-4">{{ $pro->id }}</td>
                    <td><img src="{{ asset('images/' . $pro->image) }}" width="50" class="rounded border"></td>
                    <td class="fw-bold">{{ $pro->name }}</td>
                    <td class="text-danger fw-bold">{{ number_format($pro->price) }}đ</td>
                    <td>{{ $pro->unit ?? 'Đang cập nhật' }}</td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-primary me-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEdit{{ $pro->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="{{ route('product.delete', $pro->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ní chắc chưa?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>

                <div class="modal fade" id="modalEdit{{ $pro->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow">
                            <form action="{{ route('product.update', $pro->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="original_updated_at" value="{{ $pro->updated_at }}">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Sửa sản phẩm: {{ $pro->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold">Tên sản phẩm</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $pro->name }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Loại</label>
                                            <select name="category_id" class="form-select">
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $pro->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Giá</label>
                                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ $pro->price }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Đơn vị</label>
                                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ $pro->unit }}" required>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold d-block">Ảnh hiện tại</label>
                                        <img src="{{ asset('images/' . $pro->image) }}" width="80" class="mb-2 border rounded">
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Mô tả</label>
                                        <textarea name="description" class="form-control" rows="3">{{ $pro->description }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary px-4">Lưu thay đổi.</button>
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
                    <h5 class="modal-title">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên..." required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Loại</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Đơn vị</label>
                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" placeholder="kg, hộp..." required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success px-4">Lưu ngay</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection