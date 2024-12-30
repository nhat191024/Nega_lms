@extends('master')
@section('title', 'Chi tiết lớp học')
@section('content')
<div class="container mt-4">
    <h1 class="text-center">Chi tiết Lớp học</h1>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#Info" role="tab" aria-controls="Info" aria-selected="true">Thông tin lớp học</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="students-tab" data-toggle="tab" href="#Students" role="tab" aria-controls="Students" aria-selected="false">Danh sách học sinh</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="assignments-tab" data-toggle="tab" href="#Assignments" role="tab" aria-controls="Assignments" aria-selected="false">Bài tập</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="Info" role="tabpanel" aria-labelledby="info-tab">
            <h3>Thông tin lớp học</h3>
            <p>Tên lớp: {{ $class->name }}</p>
            <p>Mã lớp: {{ $class->code }}</p>
            <p>Giảng viên: {{ $class->teacher->name }}</p>
            <p>Trạng thái: {{ $class->status ? 'Hiển thị' : 'Ẩn' }}</p>
        </div>

        <div class="tab-pane fade" id="Students" role="tabpanel" aria-labelledby="students-tab">
            <h3>Danh sách học sinh</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Tên học sinh</th>
                            <th class="text-center">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($class->students as $student)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $student->name }}</td>
                                <td class="text-center">{{ $student->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="Assignments" role="tabpanel" aria-labelledby="assignments-tab">
            <h3>Bài tập</h3>
            <p>Đây là danh sách bài tập của lớp học.</p>
            <!-- Thêm nội dung về bài tập ở đây -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
