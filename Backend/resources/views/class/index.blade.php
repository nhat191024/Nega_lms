@extends('master')
@section('title', 'Lớp học')
@section('content')
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Quản lý lớp học</h1>
            <!-- DataTales Example -->
            @foreach ($classes as $class)
                <div class="card shadow my-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <div class="d-flex">
                            <h6 class="m-0 font-weight-bold text-primary">Lớp - {{ $class->class_name }}</h6>
                            <h6 class="m-0 font-weight-bold text-primary mx-4">Giảng viên - {{ $class->teacher->name }}</h6>
                        </div>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#add-student-to-class-{{ Str::slug($class->class_name) }}">
                            Thêm học sinh
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="add-student-to-class-{{ Str::slug($class->class_name) }}" tabindex="-1"
                            aria-labelledby="modal-title-{{ Str::slug($class->class_name) }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('classes.addStudent') }}" method="post">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5"
                                                id="modal-title-{{ Str::slug($class->class_name) }}">Thêm học sinh vào
                                                {{ $class->class_name }}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                                        <div class="modal-body">
                                            <select name="student_id" class="selectpicker" data-live-search="true"
                                                data-width="100%" title="Chọn học sinh...">
                                                @foreach ($studentsNotInClass($class->id) as $student)
                                                    <option value="{{ $student->id }}" data-tokens="{{ $student->name }}">
                                                        {{ $student->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary">Lưu</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if ($class->students->isNotEmpty())
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered class-table"
                                    data-class-table="table-{{ Str::slug($class->class_name) }}"
                                    id="table-{{ Str::slug($class->class_name) }}" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tên học sinh</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($class->students as $student)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $student->name }}</td>
                                                <td class="text-center">{{ $student->email }}</td>
                                                <th class="text-center">
                                                    <form id="delete-form-{{ $class->id }}-{{ $student->id }}"
                                                        action="{{ route('classes.removeStudent', ['class_id' => $class->id, 'student_id' => $student->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="if(confirm('Bạn có chắc chắn muốn xóa học sinh {{ $student->name }} khỏi lớp {{ $class->class_name }}?')) { document.getElementById('delete-form-{{ $class->id }}-{{ $student->id }}').submit(); }">
                                                            Xóa
                                                        </button>
                                                    </form>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tên học sinh</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Tác vụ</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <div>Không có học sinh nào</div>
                    @endif
                </div>
            @endforeach
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection
