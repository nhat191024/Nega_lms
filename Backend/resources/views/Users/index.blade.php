@extends('master')
@section('title', 'Người dùng')
@section('content')
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Quản lý người dùng</h1>
            <div class="card shadow my-4">
                <div class="card-header py-3">
                    <a href={{ route('users.create') }} class="btn btn-primary">
                        <span class="text">Thêm người dùng</span>
                    </a>
                </div>
                @if ($users->isNotEmpty())
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered class-table" data-class-table="table-users" id="table-users"
                                width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Tên người dùng</th>
                                        <th class="text-center">Vai trò</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $user->name }}</td>
                                            <td class="text-center">{{ $user->email }}</td>
                                            <td class="text-center">{{ $user->username }}</td>
                                            <td class="text-center">{{ $roles[$user->role_id] }}</td>
                                            <td class="text-center">
                                                @if ($user->status == 1)
                                                    <span class="badge badge-success fs-6">Hiển thị</span>
                                                @else
                                                    <span class="badge badge-danger fs-6">Ẩn</span>
                                                @endif
                                            </td>
                                            <th class="text-center">
                                                <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                                                    class="btn btn-warning btn-sm">
                                                    Sửa
                                                </a>
                                                <a href="{{ route('users.status', ['id' => $user->id]) }}"
                                                    class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }} btn-sm">
                                                    @if ($user->status)
                                                        Ẩn
                                                    @else
                                                        Hiển thị
                                                    @endif
                                                </a>
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Tên người dùng</th>
                                        <th class="text-center">Vai trò</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    <div>Không có học sinh nào</div>
                @endif
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection