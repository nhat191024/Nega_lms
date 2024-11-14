@extends('master')

@section('title')
    <title>Cập Nhật Người Dùng</title>
@endsection

@section('content')
    <div class="container vh-100">
        <h2 class="my-4">Cập Nhật thông tin người dùng <strong>{{ $users->name }}</strong></h2>

        <a href="{{ route('users.index') }}" class="btn btn-secondary mb-4">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>

        <!-- Form tạo người dùng -->
        <form action="{{ route('users.update', ['id' => $users->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tên người dùng -->
            <div class="mb-3">
                <label for="name" class="form-label">Tên người dùng</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $users->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $users->email) }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="{{ old('username', $users->username) }}">
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Vai trò người dùng -->
            <div class="mb-3">
                <label for="role_id" class="form-label">Vai trò</label>
                <select class="form-control" id="role_id" name="role_id">
                    <option value="1" {{ old('role_id', $users->role_id) == 1 ? 'selected' : '' }}>Quản
                        trị</option>
                    <option value="2" {{ old('role_id', $users->role_id) == 2 ? 'selected' : '' }}>Giảng
                        viên</option>
                    <option value="3" {{ old('role_id', $users->role_id) == 3 ? 'selected' : '' }}>Sinh
                        viên</option>
                </select>
                @error('role_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
@endsection
