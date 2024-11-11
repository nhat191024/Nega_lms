@extends('master')

@section('title')
    <title>Admin - Users/Create</title>
@endsection

@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Create Users</h1>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Create Users</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="content">
                                <div class="d-flex justify-content-center align-items-center"
                                    style="height: 100%; padding-top: 56px;">
                                    <div class="card w-50">
                                        <div class="card-body">
                                            <h2 class="card-title">Create New</h2>
                                            @if (session('success'))
                                                <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div>
                                            @endif
                                            <form action="{{ route('users.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <!-- Tên người dùng -->
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Tên người dùng</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ old('name') }}">
                                                    @error('name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Email -->
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="{{ old('email') }}">
                                                    @error('email')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Username -->
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username"
                                                        name="username" value="{{ old('username') }}">
                                                    @error('username')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Mật khẩu -->
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Mật khẩu (Yêu cầu tối thiểu 8
                                                        ký tự)</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password">
                                                    @error('password')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Xác nhận mật khẩu -->
                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">Xác nhận Mật
                                                        khẩu</label>
                                                    <input type="password" class="form-control" id="password_confirmation"
                                                        name="password_confirmation">
                                                    @error('password_confirmation')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Vai trò người dùng -->
                                                <div class="mb-3">
                                                    <label for="role_id" class="form-label">Vai trò</label>
                                                    <select class="form-control" id="role_id" name="role_id">
                                                        <option value="">Chọn vai trò</option>
                                                        <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>
                                                            Admin</option>
                                                        <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>
                                                            Teacher</option>
                                                        <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>
                                                            User</option>
                                                    </select>
                                                    @error('role_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Nút tạo mới -->
                                                <button type="submit" class="btn btn-primary">Tạo mới</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2020</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
@endsection
