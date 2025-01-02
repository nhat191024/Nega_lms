@extends('master')
@section('title', 'Chi tiết lớp học')
@section('content')
    <div class="container mt-5">
        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách lớp học
        </a>

        <h1 class="text-center mb-4">Chi tiết lớp học: {{ $class->name }}</h1>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#Info" role="tab"
                    aria-controls="Info" aria-selected="true">Thông tin lớp học</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="students-tab" data-toggle="tab" href="#Students" role="tab"
                    aria-controls="Students" aria-selected="false">Danh sách học sinh</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="assignments-tab" data-toggle="tab" href="#Assignments" role="tab"
                    aria-controls="Assignments" aria-selected="false">Bài tập</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Tab Thông tin lớp học -->
            <div class="tab-pane fade show active" id="Info" role="tabpanel" aria-labelledby="info-tab">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3 class="card-title">Thông tin lớp học</h3>
                        <p class="card-text"><strong>Tên lớp:</strong> {{ $class->name }}</p>
                        <p class="card-text"><strong>Mã lớp:</strong> {{ $class->code }}</p>
                        <p class="card-text"><strong>Giảng viên:</strong> {{ $class->teacher->name }}</p>
                        <p class="card-text">
                            <strong>Trạng thái:</strong>
                            <span class="badge {{ $class->status === 'published' ? 'bg-success' : 'bg-danger' }}">
                                {{ $class->status === 'published' ? 'Mở khóa' : 'Khóa' }}
                            </span>
                        </p>

                        <!-- Danh mục lớp học -->
                        <div class="mt-3">
                            <strong>Danh mục lớp học:</strong>
                            @if ($class->categories->isEmpty())
                                <p>Không có danh mục cho lớp học này.</p>
                            @else
                                <div class="d-flex flex-wrap">
                                    @foreach ($class->categories as $category)
                                        <!-- Badge với kích thước vừa phải -->
                                        <span class="badge bg-info me-2 mb-2 fs-6 py-2 px-3">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>



                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{ route('classes.editClass', $class->id) }}" class="btn btn-warning me-2"
                                style="color: white;">Sửa</a>

                            <form action="{{ route('classes.toggleStatus', $class->id) }}" method="POST"
                                class="d-inline me-2">
                                @csrf
                                <input type="hidden" name="status"
                                    value="{{ $class->status === 'published' ? 'locked' : 'published' }}">
                                @if ($class->status === 'published')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-lock"></i> Khóa lớp
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-unlock"></i> Mở khóa lớp
                                    </button>
                                @endif
                            </form>

                            <a href="{{ route('classes.export', $class->id) }}" class="btn btn-success me-2">
                                <i class="fas fa-download me-2"></i>Xuất thông tin lớp học
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Danh sách học sinh -->
            <div class="tab-pane fade" id="Students" role="tabpanel" aria-labelledby="students-tab">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Danh sách học sinh</h3>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#add-student-to-class-{{ Str::slug($class->name) }}">Thêm học sinh</button>
                            <form action="{{ route('classes.import', $class->id) }}" method="POST"
                                enctype="multipart/form-data" class="d-inline-block">
                                @csrf
                                <label for="file" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Nhập danh sách học sinh
                                </label>
                                <input type="file" id="file" name="file" class="d-none"
                                    onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="thead-dark">
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
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td class="text-center">
                                                <form id="delete-form-{{ $class->id }}-{{ $student->id }}"
                                                    action="{{ route('classes.removeStudent', ['class_id' => $class->id, 'student_id' => $student->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="if(confirm('Bạn có chắc chắn muốn xóa học sinh {{ $student->name }} khỏi lớp {{ $class->name }}?')) { document.getElementById('delete-form-{{ $class->id }}-{{ $student->id }}').submit(); }">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal thêm học sinh -->
            <div class="modal fade" id="add-student-to-class-{{ Str::slug($class->name) }}" tabindex="-1"
                aria-labelledby="modal-title-{{ Str::slug($class->name) }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('classes.addStudent') }}" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-title-{{ Str::slug($class->name) }}">Thêm học sinh
                                    vào Lớp {{ $class->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <input type="hidden" name="class_id" value="{{ $class->id }}">
                            <div class="modal-body">
                                <select name="student_id" class="form-select" data-live-search="true" data-width="100%"
                                    title="Chọn học sinh...">
                                    @foreach ($studentsNotInClass as $student)
                                        <option value="{{ $student->id }}" data-tokens="{{ $student->name }}">
                                            {{ $student->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab Bài tập -->
            <div class="tab-pane fade" id="Assignments" role="tabpanel" aria-labelledby="assignments-tab">
                <div class="card mt-3" id="assignment-list">
                    <div class="card-body">
                        <h3 class="card-title">Bài tập</h3>
                        <ul class="list-group">
                            @foreach ($class->assignments as $assignment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $assignment->title }}</span> <button class="btn btn-primary btn-sm"
                                        onclick="showAssignmentDetails({{ $assignment->id }})">Xem chi tiết</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Chi tiết bài tập -->
            <div class="card mt-3 d-none" id="assignment-details">
                <div class="card-body"> <button class="btn btn-secondary mb-3" onclick="hideAssignmentDetails()"> <i
                            class="fas fa-arrow-left me-2"></i> Quay lại danh sách bài tập </button>
                    <ul class="nav nav-tabs" id="assignmentDetailsTab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" id="questions-tab" data-toggle="tab"
                                href="#Questions" role="tab" aria-controls="Questions" aria-selected="true">Câu
                                hỏi</a> </li>
                        <li class="nav-item d-none" id="scores-tab-container"> <a class="nav-link" id="scores-tab"
                                data-toggle="tab" href="#Scores" role="tab" aria-controls="Scores"
                                aria-selected="false">Điểm</a> </li>
                    </ul>
                    <div class="tab-content" id="assignmentDetailsTabContent">
                        <div class="tab-pane fade show active" id="Questions" role="tabpanel"
                            aria-labelledby="questions-tab">
                            <div class="mt-3">
                                <div id="questionsContent"> <!-- Nội dung chi tiết câu hỏi sẽ được tải vào đây --> </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Scores" role="tabpanel" aria-labelledby="scores-tab">
                            <div class="mt-3">
                                <div class="table-responsive" id="scoresContent">
                                    <!-- Nội dung chi tiết điểm sẽ được tải vào đây -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function showAssignmentDetails(assignmentId) {
            document.getElementById('assignment-list').classList.add('d-none');
            document.getElementById('assignment-details').classList.remove('d-none');

            fetch(`/class/assignment/${assignmentId}/details`)
                .then(response => response.json())
                .then(data => {
                    let questionsHtml = '';
                    let scoresHtml = '';

                    if (data.assignment.type === 'lab') {
                        questionsHtml = `<p>${data.assignment.description}</p>`;
                        document.getElementById('scores-tab-container').classList.add('d-none');
                    } else if (data.assignment.type === 'quiz') {
                        questionsHtml = data.questionsHtml;
                        scoresHtml = data.scoresHtml;
                        document.getElementById('scores-tab-container').classList.remove('d-none');
                    }

                    document.getElementById('questionsContent').innerHTML = questionsHtml;
                    document.getElementById('scoresContent').innerHTML = scoresHtml;
                })
                .catch(error => console.error('Error fetching assignment details:', error));
        }

        function hideAssignmentDetails() {
            document.getElementById('assignment-list').classList.remove('d-none');
            document.getElementById('assignment-details').classList.add('d-none');
        }

        document.getElementById('class-info-tab').addEventListener('click', function() {
            hideAssignmentDetails();
        });
    </script>

@endsection
