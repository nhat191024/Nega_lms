@extends('master')
@section('title', 'Quản lý lớp học')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3 mb-2 text-gray-800">Quản lý lớp học</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-class-modal">Thêm lớp học</button>

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
                                <!-- Mã lớp -->
                                <div class="col-md-12">
                                    <label for="code" class="form-label">Nhập mã lớp</label>
                                    <input name="code" type="text" class="form-control @error('code') is-invalid @enderror" id="code" placeholder="Vd: ABC123" value="{{ old('code') }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Tên lớp -->
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Nhập tên lớp</label>
                                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Vd: Lớp bá đạo" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Mô tả lớp -->
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Nhập mô tả</label>
                                    <input name="description" type="text" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Vd: Hơn 30 học sinh giỏi" value="{{ old('description') }}">
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Giảng viên -->
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

    <div class="row">
        @foreach ($classes as $class)
            <div class="col-md-4 mb-4">
                <div class="card class-card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $class->name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Mã lớp: {{ $class->code }}</h6>
                        <p class="card-text">Giảng viên: {{ $class->teacher->name }}</p>
                        <a href="{{ route('classes.show', $class->id) }}" class="btn btn-primary">Xem Chi tiết</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
