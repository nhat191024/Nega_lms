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
            <li class="nav-item">
                <a class="nav-link" id="certificates-tab" data-toggle="tab" href="#Certificates" role="tab"
                    aria-controls="Certificates" aria-selected="false">Cấp chứng chỉ</a>
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
                                    value="{{ $class->status === 'published' ? 'closed' : 'published' }}">
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

            <!-- Tab Bài tập -->
            <div class="tab-pane fade" id="Assignments" role="tabpanel" aria-labelledby="assignments-tab">
                <div class="card mt-3" id="assignment-list">
                    <div class="card-body">
                        <h3 class="card-title">Bài tập</h3>
                        <ul class="list-group">
                            @foreach ($class->assignments as $assignment)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5>{{ $assignment->title }}</h5>
                                            @if ($assignment->teacher)
                                                <p>Được giao bởi: {{ $assignment->teacher->name }}</p>
                                            @else
                                                <p>Được giao bởi: Không xác định</p>
                                            @endif
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

            <div class="tab-pane fade" id="Certificates" role="tabpanel" aria-labelledby="assignments-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sinh viên</th>
                                        <th>Email</th>
                                        <th>Tên lớp</th>
                                        <th>Link chứng chỉ</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->students as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $class->name }}</td>

                                            @php
                                                // Kiểm tra xem sinh viên có chứng chỉ hay chưa
                                                $certificate = $certificates->where('student_id', $student->id)->where('class_id', $class->id)->first();
                                            @endphp

                                            @if ($certificate)
                                                <td><a class="btn btn-success btn-sm" href="{{ asset($certificate->link_certificate) }}" target="_blank">Xem chứng chỉ</a></td>
                                                <td><button disabled class="btn btn-info btn-sm">Đã cấp</button></td>
                                            @else
                                                <td><button disabled class="btn btn-primary btn-sm">Chưa cấp</button></td>
                                                <td>
                                                    <form action="{{ route('generatePDF', ['student' => $student->id, 'class' => $class->id]) }}" method="post">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Bạn có chắc chắn cấp chứng chỉ cho sinh viên này?')">Cấp chứng chỉ</button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết bài tập -->
            <div class="card mt-3 d-none" id="assignment-details">
                <div class="card-body">
                    <button class="btn btn-secondary mb-3" onclick="hideAssignmentDetails()">
                        <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách bài tập
                    </button>
                    <ul class="nav nav-tabs" id="assignmentDetailsTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="questions-tab" data-toggle="tab" href="#Questions" role="tab"
                                aria-controls="Questions" aria-selected="true">Câu hỏi</a>
                        </li>
                        <li class="nav-item d-none" id="scores-tab-container">
                            <a class="nav-link" id="scores-tab" data-toggle="tab" href="#Scores" role="tab"
                                aria-controls="Scores" aria-selected="false">Điểm</a>
                        </li>
                        <li class="nav-item" id="results-tab-container">
                            <a class="nav-link" id="results-tab" data-toggle="tab" href="#Results" role="tab"
                                aria-controls="Results" aria-selected="false">Kết quả bài tập</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="assignmentDetailsTabContent">
                        <div class="tab-pane fade show active" id="Questions" role="tabpanel"
                            aria-labelledby="questions-tab">
                            <div class="mt-3">
                                <div id="questionsContent">
                                    <!-- Nội dung chi tiết câu hỏi sẽ được tải vào đây -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Scores" role="tabpanel" aria-labelledby="scores-tab">
                            <div class="mt-3">
                                <div class="table-responsive" id="scoresContent">
                                    <!-- Nội dung chi tiết điểm sẽ được tải vào đây -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Results" role="tabpanel" aria-labelledby="results-tab">
                            <div class="mt-3">
                                <div id="resultsContent">
                                    <!-- Nội dung chi tiết kết quả bài tập sẽ được tải vào đây -->
                                </div>
                            </div>
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

            <!-- Tab Danh sách học sinh -->
            <div class="tab-pane fade" id="Students" role="tabpanel" aria-labelledby="students-tab">
                <div class="card mt-3">
                    <div class="card-body">
                        <div id="messages-container"></div>

                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Danh sách học sinh</h3>
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" id="searchStudentInput" class="form-control"
                                    placeholder="Tìm kiếm học sinh" style="max-width: 300px;">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#add-student-to-class-{{ Str::slug($class->name) }}">
                                    Thêm học sinh
                                </button>
                                <a href="{{ route('classes.downloadTemplate') }}" class="btn btn-secondary">
                                    <i class="fas fa-download me-2"></i>Tải mẫu danh sách
                                </a>
                                <form id="import-students-form" method="POST" enctype="multipart/form-data"
                                    class="d-inline-block d-flex align-items-center">
                                    @csrf
                                    <div class="d-flex align-items-center">
                                        <label for="file" class="btn btn-primary mb-0">
                                            <i class="fas fa-upload me-2"></i>Nhập danh sách học sinh
                                        </label>
                                        <input type="file" id="file" name="file" class="d-none">
                                    </div>
                                </form>
                            </div>
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
                                                        onclick="if(confirm('Bạn có chắc chắn muốn xóa học sinh {{ $student->name }} khỏi lớp {{ $class->name }}?')) { document.getElementById('delete-form-{{ $class->id }}-{{ $student->id }}').submit(); }">Xóa
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

            <!-- Modal Xác Nhận Danh Sách Học Sinh -->
            <div class="modal fade" id="studentsPreviewModal" tabindex="-1" aria-labelledby="studentsPreviewModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="studentsPreviewModalLabel">Xác nhận danh sách học sinh</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tên học sinh</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="students-preview-table">
                                        <!-- Dữ liệu danh sách học sinh sẽ được chèn ở đây -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="button" id="confirm-add-students" class="btn btn-primary">Xác nhận và
                                thêm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            var activeTab = sessionStorage.getItem('activeTab');

            if (activeTab) {
                $('#' + activeTab).tab('show');
            }

            $('#myTab a').on('click', function(e) {
                var selectedTab = $(this).attr('id');
                sessionStorage.setItem('activeTab', selectedTab);
            });
        });

        function showAssignmentDetails(assignmentId) {
            document.getElementById('assignment-list').classList.add('d-none');
            document.getElementById('assignment-details').classList.remove('d-none');

            fetch(`/class/assignment/${assignmentId}/details`)
                .then(response => response.json())
                .then(data => {
                    let questionsHtml = '';
                    let scoresHtml = '';
                    let resultsHtml = '';

                    if (data.assignment.type === 'lab') {
                        questionsHtml = `<p>${data.assignment.description}</p>`;
                        resultsHtml = data.resultsHtml;
                        document.getElementById('scores-tab-container').classList.add('d-none');
                        document.getElementById('results-tab-container').classList.remove('d-none');
                    } else if (data.assignment.type === 'quiz') {
                        questionsHtml = data.questionsHtml;
                        scoresHtml = data.scoresHtml;
                        document.getElementById('scores-tab-container').classList.remove('d-none');
                        document.getElementById('results-tab-container').classList.add('d-none');
                    }

                    document.getElementById('questionsContent').innerHTML = questionsHtml;
                    document.getElementById('scoresContent').innerHTML = scoresHtml;
                    document.getElementById('resultsContent').innerHTML = resultsHtml;
                })
                .catch(error => console.error('Error fetching assignment details:', error));
        }

        function hideAssignmentDetails() {
            document.getElementById('assignment-list').classList.remove('d-none');
            document.getElementById('assignment-details').classList.add('d-none');
        }

        document.getElementById('info-tab').addEventListener('click', function() {
            location.reload();
        });
    </script>

    <script>
        document.getElementById('searchStudentInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#Students table tbody tr');

            tableRows.forEach(row => {
                const studentName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const studentEmail = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                if (studentName.includes(searchValue) || studentEmail.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script>
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

                rows.splice(0, 1);

                const previewTable = document.getElementById('students-preview-table');
                previewTable.innerHTML = '';

                rows.forEach((row, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${row[0] || ''}</td>
                    <td>${row[1] || ''}</td>
                    <td><button type="button" class="btn btn-danger" onclick="removeStudent(${index})">Xóa</button></td>
                `;
                    previewTable.appendChild(tr);
                });

                new bootstrap.Modal(document.getElementById('studentsPreviewModal')).show();
            };

            reader.readAsArrayBuffer(file);
        });

        function removeStudent(index) {
            const row = document.getElementById('students-preview-table').rows[index];
            if (row) {
                row.remove();
            }
        }

        document.getElementById('confirm-add-students').addEventListener('click', function() {
            const formData = new FormData();
            const rows = document.querySelectorAll('#students-preview-table tr');
            rows.forEach((row, index) => {
                const cells = row.querySelectorAll('td');
                formData.append(`students[${index}][name]`, cells[1].innerText);
                formData.append(`students[${index}][email]`, cells[2].innerText);
            });

            fetch('{{ route('classes.importConfirm', $class->id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('studentsPreviewModal'));
                    modal.hide();

                    if (data.success) {
                        let messages = '';

                        if (data.successMessages.length > 0) {
                            messages +=
                                '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                            messages += '<strong>Thành công!</strong>';
                            data.successMessages.forEach(message => {
                                messages += `<div>${message}</div>`;
                            });
                            messages +=
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        }

                        if (data.errorMessages.length > 0) {
                            messages +=
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                            messages += '<strong>Lỗi!</strong>';
                            data.errorMessages.forEach(message => {
                                messages += `<div>${message}</div>`;
                            });
                            messages +=
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        }

                        document.getElementById('messages-container').innerHTML = messages;
                    } else {
                        console.error('Error:', 'Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

@endsection
