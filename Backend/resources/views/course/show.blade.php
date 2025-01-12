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

            <!-- Tab Danh sách học sinh -->
            <div class="tab-pane fade" id="Students" role="tabpanel" aria-labelledby="students-tab">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3 class="card-title">Danh sách học sinh</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($course->enrollments as $index => $enrollment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $enrollment->user->name }}</td>
                                            <td>{{ $enrollment->user->email }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                            <p>Hạn nộp bài:
                                                {{ \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y') }}</p>
                                        </div>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="showAssignmentDetails({{ $assignment->id }})">Xem chi tiết</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal sửa khóa học -->
    <div class="modal fade" id="edit-course-modal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
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
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        });
    </script>

@endsection
