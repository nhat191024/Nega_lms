@extends('master')
@section('title', 'Quản lý bài tập')
@section('content')
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Quản lý bài tập</h1>
            
            <!-- Kiểm tra xem có bài tập nào không -->
            @if ($assignments->isNotEmpty())
                @foreach ($assignments as $assignment)
                    <div class="card shadow my-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <div class="d-flex">
                                <h6 class="m-0 font-weight-bold text-primary">Lớp - {{ $assignment->class->class_name }}</h6>
                                <h6 class="m-0 font-weight-bold text-primary mx-4">Tiêu đề - {{ $assignment->title }}</h6>
                            </div>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#add-assignment-{{ Str::slug($assignment->title) }}">
                                Thêm bài tập
                            </button>
                        </div>

                        <!-- Modal để thêm bài tập (hoặc bất kỳ hành động nào bạn muốn) -->
                        <div class="modal fade" id="add-assignment-{{ Str::slug($assignment->title) }}" tabindex="-1"
                            aria-labelledby="modal-title-{{ Str::slug($assignment->title) }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('assignments.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5"
                                                id="modal-title-{{ Str::slug($assignment->title) }}">Thêm bài tập vào Lớp
                                                {{ $assignment->class->class_name }}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="class_id" value="{{ $assignment->class->id }}">
                                        <div class="modal-body">
                                            <!-- Các trường nhập cho bài tập -->
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Tiêu đề</label>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Mô tả</label>
                                                <textarea class="form-control" name="description" rows="3" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="due_date" class="form-label">Ngày hết hạn</label>
                                                <input type="date" class="form-control" name="due_date" required>
                                            </div>
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

                        <!-- Nội dung bài tập -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table-assignment" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Lớp học</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Mô tả</th>
                                            <th class="text-center">Ngày hết hạn</th>
                                            <th class="text-center">Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $assignment->class->class_name }}</td>
                                            <td class="text-center">{{ $assignment->title }}</td>
                                            <td class="text-center">{{ $assignment->description }}</td>
                                            <td class="text-center">{{ $assignment->due_date }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('assignments.edit', $assignment->id) }}"
                                                    class="btn btn-warning btn-sm">Sửa</a>
                                                <form action="{{ route('assignments.destroy', $assignment->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bài tập này?')">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Chưa có bài tập nào.</p>
            @endif
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection
