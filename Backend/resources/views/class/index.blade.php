@extends('master')

@section('title', 'Quản lý lớp học')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3 mb-2 text-gray-800">Quản lý lớp học</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-class-modal">Thêm lớp học</button>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('classes.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm lớp học..." value="{{ request()->search }}">
                    <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <form action="{{ route('classes.index') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-text">Hiển thị số lượng</span>
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        <option value="25" {{ request()->get('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request()->get('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request()->get('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="list-group mb-3">
        @foreach ($classes as $class)
            <a href="{{ route('classes.show', $class->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $class->name }}</h5>
                    <small class="text-muted">Mã lớp: {{ $class->code }}</small>
                </div>
                <p class="mb-1">Giảng viên: {{ $class->teacher->name }}</p>
                <small class="text-muted">Sĩ số: {{ $class->students->count() }}</small>
            </a>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center">
        {{ $classes->appends(request()->input())->links() }}
    </div>

    <!-- Modal thêm lớp học -->
    <div class="modal fade" id="add-class-modal" tabindex="-1" aria-labelledby="modal-new-class" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('classes.addClass') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-new-class">Thêm lớp học mới</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 needs-validation" novalidate>
                            <div class="col-md-12">
                                <label for="code" class="form-label">Nhập mã lớp</label>
                                <input name="code" type="text" class="form-control @error('code') is-invalid @enderror" id="code" placeholder="Vd: ABC123" value="{{ old('code') }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="form-label">Nhập tên lớp</label>
                                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Vd: Lớp bá đạo" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label">Nhập mô tả</label>
                                <input name="description" type="text" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Vd: Hơn 30 học sinh giỏi" value="{{ old('description') }}">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="teacher_id" class="form-label">Thêm giảng viên</label>
                                <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id">
                                    <option selected disabled value="">Chọn giảng viên</option>
                                    @foreach ($teachersNotInClass as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Tạo</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
