@extends('master')

@section('title')
    <title>Admin - Users/Update</title>
@endsection

@section('content')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">
                <h1 class="h3 mb-2 text-gray-800">Cập Nhật Người Dùng</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Cập Nhật Người Dùng</h6>
                    </div>
                    <div class="card-body">
                        <div class="content">
                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; padding-top: 56px;">
                                <div class="card w-50">
                                    <div class="card-body">
                                        <h2 class="card-title">Cập Nhật Người Dùng</h2>

                                        <!-- Thông báo thành công -->
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        <form action="{{ route('users.update',['id'=>$users->id]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <!-- Tên người dùng -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Tên người dùng</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $users->name) }}">
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Email -->
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $users->email) }}">
                                                @error('email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Username -->
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $users->username) }}">
                                                @error('username')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Vai trò người dùng -->
                                            <div class="mb-3">
                                                <label for="role_id" class="form-label">Vai trò</label>
                                                <select class="form-control" id="role_id" name="role_id">
                                                    <option value="1" {{ old('role_id', $users->role_id) == 1 ? 'selected' : '' }}>Admin</option>
                                                    <option value="2" {{ old('role_id', $users->role_id) == 2 ? 'selected' : '' }}>Teacher</option>
                                                    <option value="3" {{ old('role_id', $users->role_id) == 3 ? 'selected' : '' }}>User</option>
                                                </select>
                                                @error('role_id')
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
