@extends('master')

@section('title', 'Quản lý khóa học')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3 mb-2 text-gray-800">Quản lý khóa học</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-course-modal">Thêm khóa học</button>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('courses.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm khóa học..." value="{{ request()->search }}">
                    <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <form action="{{ route('courses.index') }}" method="GET">
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
        @foreach ($courses as $course)
            <a href="{{ route('courses.show', $course->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $course->name }}</h5>
                    <small class="text-muted">Mã khóa học: {{ $course->code }}</small>
                </div>
                <p class="mb-1">Mô tả: {{ $course->description }}</p>
                <small class="text-muted">Trạng thái: {{ $course->status == 'published' ? 'Đã xuất bản' : 'Đã lưu trữ' }}</small>
            </a>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center">
        {{ $courses->appends(request()->input())->links() }}
    </div>

    <!-- Modal thêm khóa học -->
    <div class="modal fade" id="add-course-modal" tabindex="-1" aria-labelledby="modal-new-course" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('courses.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-new-course">Thêm khóa học mới</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 needs-validation" novalidate>
                            <div class="col-md-12">
                                <label for="code" class="form-label">Nhập mã khóa học</label>
                                <input name="code" type="text" class="form-control @error('code') is-invalid @enderror" id="code" placeholder="Vd: CSE101" value="{{ old('code') }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="form-label">Nhập tên khóa học</label>
                                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Vd: Lập trình căn bản" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label">Nhập mô tả</label>
                                <input name="description" type="text" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Vd: Khóa học giới thiệu về lập trình" value="{{ old('description') }}">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                                    <option selected disabled value="">Chọn trạng thái khóa học</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Xuất bản</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                                </select>
                                @error('status')
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
