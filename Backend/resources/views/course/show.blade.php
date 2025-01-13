@extends('master')

@section('title', 'Chi tiết khóa học')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách khóa học
        </a>

        <h1 class="text-center mb-4">Chi tiết khóa học: {{ $course->name }}</h1>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#Info" role="tab"
                    aria-controls="Info" aria-selected="true">Thông tin khóa học</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="students-tab" data-bs-toggle="tab" href="#Students" role="tab"
                    aria-controls="Students" aria-selected="false">Danh sách học sinh</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="assignments-tab" data-bs-toggle="tab" href="#Assignments" role="tab"
                    aria-controls="Assignments" aria-selected="false">Bài học</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Tab Thông tin khóa học -->
            <div class="tab-pane fade show active" id="Info" role="tabpanel" aria-labelledby="info-tab">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3 class="card-title">Thông tin khóa học</h3>
                        <p class="card-text"><strong>Tên khóa học:</strong> {{ $course->name }}</p>
                        <p class="card-text"><strong>Mã khóa học:</strong> {{ $course->code }}</p>
                        <p class="card-text"><strong>Mô tả:</strong> {{ $course->description }}</p>
                        <p class="card-text">
                            <strong>Trạng thái:</strong>
                            <span class="badge {{ $course->status === 'published' ? 'bg-success' : 'bg-danger' }}">
                                {{ $course->status === 'published' ? 'Đã xuất bản' : 'Đã lưu trữ' }}
                            </span>
                        </p>

                        <div class="d-flex justify-content-start mt-3">
                            <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal"
                                data-bs-target="#edit-course-modal" style="color: white;">
                                <i class="fas fa-edit me-2"></i>Sửa
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Bài tập -->
            <div class="tab-pane fade" id="Assignments" role="tabpanel" aria-labelledby="assignments-tab">
                <div class="card mt-3" id="assignment-list">
                    <div class="card-body">
                        <h3 class="card-title">Bài tập</h3>
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#add-assignment-modal">
                            <i class="fas fa-plus-circle me-2"></i>Thêm bài học
                        </button>
                        <ul class="list-group">
                            @foreach ($course->assignments as $assignment)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5>{{ $assignment->title }}</h5>
                                            <p>Phân loại: {{ $assignment->type == 'quiz' ? 'Quiz' : 'Lab' }}</p>
                                            <p>Thời gian làm: {{ $assignment->duration }} phút</p>
                                            <p>Số câu hỏi: {{ $assignment->courseQuizzes->count() . ' câu' }}</p>
                                            <p>Hạn nộp bài:
                                                {{ \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#assignmentDetailsModal_{{ $assignment->id }}">
                                                Xem chi tiết
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm mx-2" data-bs-toggle="modal"
                                                data-bs-target="#edit-assignment-modal_{{ $assignment->id }}">
                                                Sửa bài tập
                                            </button>
                                            <form
                                                action="{{ route('courses.assignments.delete', ['course' => $course->id, 'assignment' => $assignment->id]) }}"
                                                method="post"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="assignmentDetailsModal_{{ $assignment->id }}"
                                            tabindex="-1"
                                            aria-labelledby="assignmentDetailsModalLabel{{ $assignment->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="assignmentDetailsModalLabel{{ $assignment->id }}">Chi tiết
                                                            bài tập {{ $assignment->title }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-4">
                                                            <h6 class="font-weight-bold">Video hướng dẫn:</h6>
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <iframe class="embed-responsive-item"
                                                                    src="{{ $assignment->video_url }}"
                                                                    allowfullscreen></iframe>
                                                            </div>
                                                        </div>
                                                        <div class="accordion" id="accordionExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingOne">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseOne"
                                                                        aria-expanded="false" aria-controls="collapseOne">
                                                                        Danh sách câu hỏi
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseOne" class="accordion-collapse collapse"
                                                                    aria-labelledby="headingOne"
                                                                    data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body">
                                                                        <ul class="list-group">
                                                                            @foreach ($assignment->courseQuizzes as $courseQuiz)
                                                                                <li class="list-group-item">
                                                                                    {{ $courseQuiz->quiz->question }}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal sửa bài học --}}
                                        <div class="modal fade" id="edit-assignment-modal_{{ $assignment->id }}"
                                            tabindex="-1" aria-labelledby="" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form
                                                    action="{{ route('courses.assignments.update', ['course' => $course->id, 'assignment' => $assignment->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="addAssignmentModalLabel">Sửa bài
                                                                học {{ $assignment->title }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="assignment_title" class="form-label">Tiêu đề
                                                                    bài học</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $assignment->title }}"
                                                                    id="assignment_title" name="title" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="assignment_video_url" class="form-label">URL
                                                                    video</label>
                                                                <input type="url" class="form-control"
                                                                    value="{{ $assignment->video_url }}"
                                                                    id="assignment_video_url" name="video_url" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="assignment_description" class="form-label">Mô
                                                                    tả</label>
                                                                <textarea class="form-control" id="assignment_description" name="description" rows="3" required>{{ $assignment->description }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="assignment_duration" class="form-label">Thời
                                                                    lượng (phút)</label>
                                                                <input type="number" value="{{ $assignment->duration }}"
                                                                    class="form-control" id="assignment_duration"
                                                                    name="duration">
                                                            </div>
                                                            <div class="mb-4">
                                                                <label for="number_of_questions" class="form-label">Số
                                                                    lượng câu hỏi</label>
                                                                <div class="input-group">
                                                                    <input class="form-control" type="number"
                                                                        value="{{ $assignment->courseQuizzes->count() }}"
                                                                        min="5" max="100"
                                                                        name="number_of_questions"
                                                                        id="number_of_questions"
                                                                        aria-label="Số lượng câu hỏi">
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <label for="quizSelect" class="form-label">Thuộc bộ câu
                                                                    hỏi</label>
                                                                <select name="quiz_select" id="quizSelect"
                                                                    class="form-select" aria-label="Chọn bộ câu hỏi"
                                                                    data-live-search="true">
                                                                    @php
                                                                        $checkID;
                                                                    @endphp
                                                                    @foreach ($assignment->courseQuizzes as $courseQuiz)
                                                                        <option
                                                                            value="{{ $courseQuiz->quiz->quizPackage->id }}">
                                                                            {{ $courseQuiz->quiz->quizPackage->title }}
                                                                        </option>
                                                                        @php
                                                                            $checkID =
                                                                                $courseQuiz->quiz->quizPackage->id;
                                                                        @endphp
                                                                    @break
                                                                @endforeach
                                                                @if (!empty($checkID))
                                                                    @foreach ($quizPackages as $quizPackage)
                                                                        @if ($courseQuiz->quiz->quizPackage->id === $quizPackage->id)
                                                                            @continue
                                                                        @endif
                                                                        <option value="{{ $quizPackage->id }}">
                                                                            {{ $quizPackage->title }}</option>
                                                                    @endforeach
                                                                @else
                                                                    <option value="">Chọn bộ câu hỏi</option>
                                                                    @foreach ($quizPackages as $quizPackage)
                                                                        <option value="{{ $quizPackage->id }}">
                                                                            {{ $quizPackage->title }}</option>
                                                                    @endforeach
                                                                @endif

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-primary">Cập
                                                            nhật</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tab Danh sách học sinh -->
        <div class="tab-pane fade" id="Students" role="tabpanel" aria-labelledby="students-tab">
            <div class="card mt-3">
                <div class="card-body">
                    <h3 class="card-title">Danh sách học sinh</h3>
                    <div id="messages-container"></div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-primary mb-3 me-2" data-bs-toggle="modal"
                            data-bs-target="#addStudentModal">
                            <i class="fas fa-plus-circle me-2"></i> Thêm Học Sinh
                        </button>

                        <a href="{{ route('courses.downloadTemplate') }}" class="btn btn-secondary mb-3 me-2">
                            <i class="fas fa-download me-2"></i>Tải mẫu danh sách
                        </a>

                        <form id="import-students-form" method="POST" enctype="multipart/form-data"
                            action="{{ route('courses.importConfirm', $course->id) }}"
                            class="d-inline-block d-flex align-items-center mb-3">
                            @csrf
                            <div class="d-flex align-items-center">
                                <label for="file" class="btn btn-primary mb-0">
                                    <i class="fas fa-upload me-2"></i>Nhập danh sách học sinh
                                </label>
                                <input type="file" id="file" name="file" class="d-none"
                                    onchange="previewStudents(this)">
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course->enrollments as $index => $enrollment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $enrollment->user->name }}</td>
                                        <td>{{ $enrollment->user->email }}</td>
                                        <td>
                                            <form
                                                action="{{ route('courses.removeStudent', ['courseId' => $course->id]) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="student_ids[]"
                                                    value="{{ $enrollment->user->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                </button>
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

        <!-- Modal Thêm Học Sinh -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('courses.add-student', $course->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStudentModalLabel">Thêm Học Sinh Mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="student_ids">Chọn học sinh</label>
                                <select class="form-select" id="student_ids" name="student_ids[]" multiple>
                                    @forelse ($studentsNotInCourse as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }}
                                            ({{ $student->email }})</option>
                                    @empty
                                        <option disabled>Không có học sinh nào chưa tham gia khóa học</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Thêm Học Sinh</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal hiển thị danh sách học sinh đã được tải lên -->
        <div class="modal" tabindex="-1" id="studentsPreviewModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Danh sách học sinh</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="students-preview-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên học sinh</th>
                                    <th>Email</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary" id="confirm-add-students">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa khóa học -->
<div class="modal fade" id="edit-course-modal" tabindex="-1" aria-labelledby="editCourseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('courses.update', $course->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCourseModalLabel">Sửa khóa học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Mã khóa học</label>
                        <input type="text" class="form-control" id="course_code" name="code"
                            value="{{ $course->code }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Tên khóa học</label>
                        <input type="text" class="form-control" id="course_name" name="name"
                            value="{{ $course->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="course_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="course_description" name="description" rows="3" required>{{ $course->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="course_status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="course_status" name="status" required>
                            <option value="published" {{ $course->status == 'published' ? 'selected' : '' }}>Đã xuất
                                bản</option>
                            <option value="archived" {{ $course->status == 'archived' ? 'selected' : '' }}>Đã lưu trữ
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal thêm bài học -->
<div class="modal fade" id="add-assignment-modal" tabindex="-1" aria-labelledby="addAssignmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('courses.assignments.store', $course->id) }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAssignmentModalLabel">Thêm bài học mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assignment_title" class="form-label">Tiêu đề bài học</label>
                        <input type="text" class="form-control" id="assignment_title" name="title"
                            value="{{ old('title') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="assignment_video_url" class="form-label">URL video</label>
                        <input type="url" class="form-control" id="assignment_video_url" name="video_url"
                            value="{{ old('video_url') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="assignment_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="assignment_description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="assignment_duration" class="form-label">Thời lượng (phút)</label>
                        <input type="number" class="form-control" id="assignment_duration" name="duration"
                            value="{{ old('duration') }}">
                    </div>
                    <div class="mb-4">
                        <label for="number_of_questions" class="form-label">Số lượng câu hỏi</label>
                        <div class="input-group">
                            <input class="form-control" type="number" value="10" min="5" max="100"
                                name="number_of_questions" id="number_of_questions" aria-label="Số lượng câu hỏi">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="quizSelect" class="form-label">Chọn bộ câu hỏi</label>
                        <select name="quiz_select" id="quizSelect" class="form-select" aria-label="Chọn bộ câu hỏi"
                            data-live-search="true">
                            <option value="" disabled selected>Chọn bộ câu hỏi</option>
                            @foreach ($quizPackages as $quizPackage)
                                <option value="{{ $quizPackage->id }}">{{ $quizPackage->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm bài học</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Lấy trạng thái tab đã chọn từ sessionStorage
        var activeTab = sessionStorage.getItem('activeTab');

        if (activeTab) {
            // Nếu có tab đã lưu, mở tab đó
            var tab = new bootstrap.Tab(document.getElementById(activeTab));
            tab.show();
        }

        // Lắng nghe sự kiện khi người dùng chọn tab
        var tabs = document.querySelectorAll('#myTab a');
        tabs.forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function() {
                // Lưu tab đã chọn vào sessionStorage
                sessionStorage.setItem('activeTab', this.id);
            });
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tab Bootstrap
        var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'));
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });

        // Khởi tạo Selectpicker (nếu có)
        var quizSelect = document.getElementById('quizSelect');
        if (quizSelect) {
            new bootstrap.Select(quizSelect);
        }
        $('.selectpicker').selectpicker();
    });

    // Xử lý file Excel khi chọn
    document.getElementById('file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {
                type: 'array'
            });
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            const rows = XLSX.utils.sheet_to_json(sheet, {
                header: 1
            });

            rows.splice(0, 1); // Xóa dòng đầu tiên nếu là tiêu đề

            const previewTable = document.querySelector('#students-preview-table tbody');
            previewTable.innerHTML = '';

            rows.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${row[0] || ''}</td>
                    <td>${row[1] || ''}</td>
                    <td><button type="button" class="btn btn-danger" onclick="removeStudent(this)">Xóa</button></td>
                `;
                previewTable.appendChild(tr);
            });

            new bootstrap.Modal(document.getElementById('studentsPreviewModal')).show();
        };
        reader.readAsArrayBuffer(file);
    });

    // Xóa học sinh khỏi bảng trước khi xác nhận
    function removeStudent(button) {
        const row = button.closest('tr');
        if (row) row.remove();
    }

    // Xử lý khi nhấn nút "Xác nhận thêm học sinh"
    document.getElementById('confirm-add-students').addEventListener('click', function() {
        const formData = new FormData();
        const rows = document.querySelectorAll('#students-preview-table tbody tr');

        // Lấy tên và email học sinh từ bảng preview
        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            formData.append(`students[${index}][name]`, cells[1].innerText.trim());
            formData.append(`students[${index}][email]`, cells[2].innerText.trim());
        });

        // Gửi dữ liệu tới server
        fetch('{{ route('courses.importConfirm', $course->id) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('studentsPreviewModal'));
                modal.hide(); // Đóng modal sau khi thêm học sinh

                let messages = '';
                if (data.successMessages && data.successMessages.length > 0) {
                    messages += `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Thành công!</strong>
                        ${data.successMessages.map(msg => `<div>${msg}</div>`).join('')}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                }
                if (data.errorMessages && data.errorMessages.length > 0) {
                    messages += `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Lỗi!</strong>
                        ${data.errorMessages.map(msg => `<div>${msg}</div>`).join('')}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                }
                document.getElementById('messages-container').innerHTML = messages;

                // Cập nhật bảng danh sách học sinh với những học sinh mới
                const studentTable = document.querySelector('#Students table tbody');
                data.students.forEach((student, index) => {
                    const newRow = studentTable.insertRow();
                    newRow.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${student.name}</td>
                    <td>${student.email}</td>
                    <td>
                        <form action="{{ route('courses.removeStudent', ['courseId' => $course->id]) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="student_ids[]" value="${student.id}">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </td>
                `;
                });
            });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

@endsection
