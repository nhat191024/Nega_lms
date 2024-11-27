@extends('master')
@section('title', 'Lớp học')
@section('content')
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-2 text-gray-800">Quản lý lớp học</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-class-modal">
                    Thêm lớp học
                </button>
                <!-- Modal -->
                <div class="modal fade" id="add-class-modal" tabindex="-1" aria-labelledby="modal-new-class"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('classes.addClass') }}" method="post">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modal-new-class">Thêm lớp học mới</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                {{-- <input type="hidden" name="class_id" value="{{ $class->id }}"> --}}
                                <div class="modal-body">
                                    <form class="row g-3 needs-validation" novalidate>
                                        <div class="col-md-12">
                                            <label for="validationCustom01" class="form-label">Nhập tên lớp</label>
                                            <input name="className" type="text"
                                                class="form-control @error('className') is-invalid @enderror"
                                                id="validationCustom01" placeholder="Vd: Lớp bá đạo"
                                                value="{{ old('className') }}">
                                            <p class="fs-6 text-danger">
                                                @error('className')
                                                    {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="validationCustom01" class="form-label">Nhập mô tả</label>
                                            <input name="classDescription" type="text"
                                                class="form-control @error('classDescription') is-invalid @enderror"
                                                id="validationCustom01" placeholder="Vd: Hơn 30 học sinh giỏi"
                                                value="{{ old('classDescription') }}">
                                            <p class="fs-6 text-danger">
                                                @error('classDescription')
                                                    {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="validationCustom04" class="form-label">Thêm giảng viên</label>
                                            <select name="teacherID"
                                                class="form-select @error('teacherID') is-invalid @enderror"
                                                id="validationCustom04">
                                                <option selected disabled value="">Chọn giảng viên</option>
                                                @foreach ($teachersNotInClass as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="fs-6 text-danger">
                                                @error('teacherID')
                                                    {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Tạo</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- DataTales Example -->
            @foreach ($classes as $class)
                <div class="card shadow my-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <div class="d-flex">
                            <h6 class="m-0 font-weight-bold text-primary">Lớp - {{ $class->class_name }}</h6>
                            <h6 class="m-0 font-weight-bold text-primary mx-4">Giảng viên - {{ $class->teacher->name }}
                                <a href="{{ route('classes.assignments.byClass', ['class_id' => $class->id]) }}" class="btn btn-info">Bài tập</a>

                            </h6>
                        </div>

                        <div>
                            @if ($class->status === 1)
                                <a class="btn btn-danger"
                                    onclick="event.preventDefault(); if (confirm('Bạn chắc chắn muốn ẩn lớp {{ $class->name }} chứ?')) { window.location.href = '{{ route('classes.hideClass', ['class_id' => $class->id]) }}'; }"
                                    type="submit">Ẩn lớp</a>
                            @else
                                <button class="btn btn-secondary"
                                    onclick="event.preventDefault(); if (confirm('Bạn chắc chắn muốn hiện Lớp {{ $class->name }} chứ?')) { window.location.href = '{{ route('classes.hideClass', ['class_id' => $class->id]) }}'; }"
                                    type="submit">Hiển thị</button>
                            @endif

                            <button type="button" class="btn btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#add-student-to-class-{{ Str::slug($class->class_name) }}">
                                Thêm học sinh
                            </button>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="add-student-to-class-{{ Str::slug($class->class_name) }}"
                            tabindex="-1" aria-labelledby="modal-title-{{ Str::slug($class->class_name) }}"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('classes.addStudent') }}" method="post">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5"
                                                id="modal-title-{{ Str::slug($class->class_name) }}">Thêm học sinh vào
                                                Lớp
                                                {{ $class->class_name }}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                                        <div class="modal-body">
                                            <select name="student_id" class="selectpicker" data-live-search="true"
                                                data-width="100%" title="Chọn học sinh...">
                                                @foreach ($studentsNotInClass($class->id) as $student)
                                                    <option value="{{ $student->id }}"
                                                        data-tokens="{{ $student->name }}">
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
                        <div class="m-3 fw-bold text-warning">Không có học sinh nào</div>
                    @endif
                </div>
            @endforeach
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection
