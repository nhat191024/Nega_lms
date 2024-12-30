@extends('master')
@section('title', 'Sửa lớp học')

@section('content')
    <div id="content">
        <div class="container">
            <h1>Sửa lớp học: {{ $class->class_name }}</h1>

            <form action="{{ route('classes.updateClass', $class->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="ccode" class="form-label">Mã lớp</label>
                    <input type="text" class="form-control" id="code" name="ccode"
                        value="{{ $class->code }}">
                    <p class="fs-6 text-danger">
                        @error('code')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="mb-3">
                    <label for="nname" class="form-label">Tên lớp</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ $class->name }}">
                    <p class="fs-6 text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả lớp</label>
                    <input type="text" class="form-control" id="description" name="description"
                        value="{{ $class->description }}">
                    <p class="fs-6 text-danger">
                        @error('description')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="mb-3">
                    <label for="teacherID" class="form-label">Giảng viên</label>
                    <select name="teacherID" class="form-select">
                        @foreach ($teachersNotInClass as $teacher)
                            <option value="{{ $teacher->id }}" {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="fs-6 text-danger">
                        @error('teacherID')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <button type="submit" class="btn btn-primary"
                    onclick="if(confirm('Bạn có chắc chắn muốn cập nhật thông tin lớp học này không?')) { document.submit()}">Cập
                    nhật</button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>

@endsection
