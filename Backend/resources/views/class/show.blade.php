@extends('master')
@section('title', 'Chi tiết lớp học')
@section('content')
<div class="container mt-4">
    <h1 class="text-center">Chi tiết Lớp học</h1>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-warning mr-2">
            <a href="{{ route('classes.editClass', $class->id) }}" class="text-white text-decoration-none">Sửa</a>
        </button>
        @if ($class->status === 1)
            <a class="btn btn-danger mr-2"
                onclick="event.preventDefault(); if (confirm('Bạn chắc chắn muốn ẩn lớp {{ $class->name }} chứ?')) { window.location.href = '{{ route('classes.hideClass', ['class_id' => $class->id]) }}'; }"
                type="submit">Ẩn lớp</a>
        @else
            <button class="btn btn-secondary mr-2"
                onclick="event.preventDefault(); if (confirm('Bạn chắc chắn muốn hiện lớp {{ $class->name }} chứ?')) { window.location.href = '{{ route('classes.hideClass', ['class_id' => $class->id]) }}'; }"
                type="submit">Hiển thị</button>
        @endif
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-student-modal">Thêm học sinh</button>
    </div>

    <!-- Modal thêm học sinh -->
    <div class="modal fade" id="add-student-modal" tabindex="-1" aria-labelledby="modal-add-student" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('classes.addStudent') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-add-student">Thêm học sinh</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <div class="row g-3 needs-validation" novalidate>
                            <div class="col-md-12">
                                <label for="student_id" class="form-label">Chọn học sinh</label>
                                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" id="student_id">
                                    <option selected disabled value="">Chọn học sinh</option>
                                    @foreach ($studentsNotInClass as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Thêm</button>
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

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#Info" role="tab" aria-controls="Info" aria-selected="true">Thông tin lớp học</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="students-tab" data-toggle="tab" href="#Students" role="tab" aria-controls="Students" aria-selected="false">Danh sách học sinh</a>
        </li>
        <li class="nav-item">
            <a class="nav-link