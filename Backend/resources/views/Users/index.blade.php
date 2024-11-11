@extends('master')

@section('title')
<title>Admin - Users</title>
@endsection

@section('content')
            <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Tables Users</h1>

                    <button class="btn btn-success btn-icon-split mb-4">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <a href={{ route('users.create') }} class="text-white">
                            <span class="text">Create</span>
                        </a>
                    </button>
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Tables</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $roles[$user->role_id] ?? 'Unknown' }}</td>
                                            <td>{{ $user->status ? 'Hiển thị' : 'Ẩn' }}</td>
                                            <td>
                                                <a href="{{route('users.edit', ['id' => $user->id])}}" class="btn btn-warning btn-sm">Sửa</a>
                                                <a href="{{ route('users.status', ['id' => $user->id]) }}" class="btn 
                                                    {{ $user->status ? 'btn-danger' : 'btn-success' }} btn-sm">
                                                    @if ($user->status) 
                                                        Ẩn
                                                    @else 
                                                        Hiển thị
                                                    @endif
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
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