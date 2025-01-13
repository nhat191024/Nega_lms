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
                                            <p>Số câu hỏi: {{ $assignment->courseQuizzes->count() . ' câu' }}</p>
                                            <p>Hạn nộp bài: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignmentDetailsModal_{{ $assignment->id }}">
                                                Xem chi tiết
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#edit-assignment-modal_{{ $assignment->id }}">
                                                Sửa bài tập
                                            </button>
                                            <form action="{{ route('courses.assignments.delete', ['course' => $course->id, 'assignment' => $assignment->id]) }}" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    
                                        <!-- Modal -->
                                        <div class="modal fade" id="assignmentDetailsModal_{{ $assignment->id }}" tabindex="-1" aria-labelledby="assignmentDetailsModalLabel{{ $assignment->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="assignmentDetailsModalLabel{{ $assignment->id }}">Chi tiết bài tập {{ $assignment->title }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-4">
                                                            <h6 class="font-weight-bold">Video hướng dẫn:</h6>
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <iframe class="embed-responsive-item" src="{{ $assignment->video_url }}" allowfullscreen></iframe>
                                                            </div>
                                                        </div>
                                                        <div class="accordion" id="accordionExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingOne">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                                        Danh sách câu hỏi
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
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
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal sửa bài học --}}
                                        <div class="modal fade" id="edit-assignment-modal_{{ $assignment->id }}" tabindex="-1" aria-labelledby=""
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('courses.assignments.update', ['course' => $course->id, 'assignment' => $assignment->id]) }}" method="post">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="addAssignmentModalLabel">Sửa bài học {{ $assignment->title }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="assignment_title" class="form-label">Tiêu đề bài học</label>
                                                                <input type="text" class="form-control" value="{{ $assignment->title }}" id="assignment_title" name="title"
                                                                     required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="assignment_video_url" class="form-label">URL video</label>
                                                                <input type="url" class="form-control" value="{{ $assignment->video_url }}" id="assignment_video_url" name="video_url"
                                                                    required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="assignment_description" class="form-label">Mô tả</label>
                                                                <textarea class="form-control" id="assignment_description" name="description" rows="3" required>{{ $assignment->description }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="assignment_duration" class="form-label">Thời lượng (phút)</label>
                                                                <input type="number" value="{{ $assignment->duration }}" class="form-control" id="assignment_duration" name="duration"
                                                                   >
                                                            </div>
                                                            <div class="mb-4">
                                                                <label for="number_of_questions" class="form-label">Số lượng câu hỏi</label>
                                                                <div class="input-group">
                                                                    <input class="form-control" type="number" value="{{ $assignment->courseQuizzes->count() }}" min="10" max="100" name="number_of_questions" id="number_of_questions" aria-label="Số lượng câu hỏi">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-4">
                                                                <label for="quizSelect" class="form-label">Thuộc bộ câu hỏi</label>
                                                                <select name="quiz_select" id="quizSelect" class="form-select" aria-label="Chọn bộ câu hỏi" data-live-search="true">
                                                                    @php
                                                                        $checkID;
                                                                    @endphp
                                                                    @foreach ($assignment->courseQuizzes as $courseQuiz)
                                                                        <option value="{{ $courseQuiz->quiz->quizPackage->id }}">{{ $courseQuiz->quiz->quizPackage->title }}</option>
                                                                        @php
                                                                            $checkID = $courseQuiz->quiz->quizPackage->id;
                                                                        @endphp
                                                                        @break
                                                                    @endforeach
                                                                    @if (!empty($checkID))
                                                                        @foreach ($quizPackages as $quizPackage)
                                                                            @if ($courseQuiz->quiz->quizPackage->id === $quizPackage->id)
                                                                                @continue
                                                                            @endif
                                                                            <option value="{{ $quizPackage->id }}">{{ $quizPackage->title }}</option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="">Chọn bộ câu hỏi</option>
                                                                        @foreach ($quizPackages as $quizPackage)
                                                                            <option value="{{ $quizPackage->id }}">{{ $quizPackage->title }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
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
                        <div class="mb-4">
                            <label for="number_of_questions" class="form-label">Số lượng câu hỏi</label>
                            <div class="input-group">
                                <input class="form-control" type="number" value="10" min="10" max="100" name="number_of_questions" id="number_of_questions" aria-label="Số lượng câu hỏi">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="quizSelect" class="form-label">Chọn bộ câu hỏi</label>
                            <select name="quiz_select" id="quizSelect" class="form-select" aria-label="Chọn bộ câu hỏi" data-live-search="true">
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
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });

            var quizSelect = document.getElementById('quizSelect');
            if (quizSelect) {
                new bootstrap.Select(quizSelect);
            }
        });
    </script>

@endsection
