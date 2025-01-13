@extends('master')

@section('title', 'Thêm Người dùng')

@section('content')
    <div class="container min-vh-100 d-flex flex-column">
        <h2 class="my-4">Tạo người dùng mới</h2>

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <a href="{{ route('users.index') }}" class="btn btn-secondary mb-4">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" id="avatar" class="form-control">
                @error('avatar')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Tên người dùng</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role_id">Vai trò</label>
                <select name="role_id" id="role_id" class="form-control">
                    <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Quản trị</option>
                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Giảng viên</option>
                    <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Sinh viên</option>
                    <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>Người giám sát</option>
                </select>
                @error('role_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"
                onclick="return confirm('Bạn có muốn tạo người dùng mới không?')">
                Tạo
            </button>
        </form>
        <br>
    </div>
@endsection
