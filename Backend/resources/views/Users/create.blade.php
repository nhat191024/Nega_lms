@extends('master')

@section('title', ' Thêm Người dùng')

@section('content')
    <div class="container vh-100">
        <h2 class="my-4">Tạo người dùng mới</h2>

        <a href="{{ route('users.index') }}" class="btn btn-secondary mb-4">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>

        <!-- Form tạo người dùng -->
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <!-- Tên -->
            <div class="form-group">
                <label for="name">Tên người dùng</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username -->
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}">
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role_id">Vai trò</label>
                <select name="role_id" id="role_id" class="form-control">
                    <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Quản trị</option>
                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Giảng viên</option>
                    <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Sinh viên</option>
                </select>
                @error('role_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Tạo</button>
        </form>
    </div>
@endsection