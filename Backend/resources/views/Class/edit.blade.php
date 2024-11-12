@extends('master')

@section('title')
    <title>Cập Nhật Lớp Học</title>
@endsection

@section('content')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">
                <h1 class="h3 mb-2 text-gray-800">Cập Nhật Lớp Học</h1>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary mb-4">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Cập Nhật Lớp Học</h6>
                    </div>
                    <div class="card-body">
                        <div class="content">
                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; padding-top: 56px;">
                                <div class="card w-50">
                                    <div class="card-body">
                                        <h2 class="card-title">Cập Nhật Lớp Học</h2>

                                        <!-- Thông báo thành công -->
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        <form action="{{ route('classes.update',['id' => $classes->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <!-- Tên Lớp Học -->
                                            <div class="mb-3">
                                                <label for="class_name" class="form-label">Tên Lớp</label>
                                                <input type="text" class="form-control" id="class_name" name="class_name" value="{{ old('class_name', $classes->class_name) }}">
                                                @error('class_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Mô tả Lớp Học -->
                                            <div class="mb-3">
                                                <label for="class_description" class="form-label">Mô Tả Lớp Học</label>
                                                <textarea class="form-control" id="class_description" name="class_description">{{ old('class_description', $classes->class_description) }}</textarea>
                                                @error('class_description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                           <!-- Giáo viên -->
                                    <div class="mb-3">
                                        <label for="teacher_id" class="form-label">Giáo viên</label>
                                        <select class="form-control" id="teacher_id" name="teacher_id" required>
                                            <option value="">Select Teacher</option>
                                            <option value="">1</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('teacher_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
