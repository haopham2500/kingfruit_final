@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="fw-bold">Quản lý danh mục</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle me-1"></i> Thêm danh mục mới
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Tên danh mục</th>
                        <th>Ngày tạo</th>
                        <th class="text-end pe-4">Hành động</th>
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
                            <a href="{{ route('category.delete', $cat->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ní chắc chắn muốn xóa danh mục này?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('category.update', $cat->id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa danh mục</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">Tên danh mục</label>
                                        <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
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
                    <h5 class="modal-title">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Tên danh mục</label>
                    <input type="text" name="name" class="form-control" placeholder="Ví dụ: Trái cây nhập khẩu" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Thêm ngay</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection