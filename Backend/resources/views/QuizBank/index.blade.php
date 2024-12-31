@extends('master')

@section('title', 'Quản lý kho quiz')

@section('content')
    <style>
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }
    </style>
    <section class="controller px-3">
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                    aria-controls="home" aria-selected="true">Kho quiz</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">Kho quiz đã ẩn</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab"
                    aria-controls="contact" aria-selected="false">Kho quiz đã xóa</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabsContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                @if ($quizBank->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#createQuizBank">
                                Tạo kho quiz
                            </button>
                            <div class="modal fade" id="createQuizBank" tabindex="-1" aria-labelledby="createQuizBank"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <!-- Header Modal -->
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createQuizBank">Tạo kho quiz</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <!-- Body Modal -->
                                        <div class="modal-body">
                                            <form action="{{ route('quiz-bank.create') }}">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Tên kho</label>
                                                    <input type="text" class="form-control" id="title"
                                                        placeholder="Nhập tên" name="quiz_name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Mô tả</label>
                                                    <textarea name="quiz_description" class="form-control" id="description" rows="3" placeholder="Nhập mô tả"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="category" class="form-label">Danh mục</label>
                                                    <select id="categories" name="categories[]"
                                                        class="selectpicker form-select" multiple size="3">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $('#categories').selectpicker();
                                                        });
                                                    </script>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Phạm vi</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Trạng thái</label>
                                                    <select class="form-select" name="status" id="status">
                                                        <option value="published">Công khai
                                                        </option>
                                                        <option value="private">Ẩn</option>
                                                        <option value="closed">Hủy</option>
                                                    </select>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Tạo</button>
                                            </form>
                                        </div>

                                        <!-- Footer Modal -->
                                        <div class="modal-footer">

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Người tạo</th>
                                            <th>Tên kho</th>
                                            <th>Mô tả</th>
                                            <th>Thuộc danh mục</th>
                                            <th>Bộ câu hỏi</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quizBank as $quiz)
                                            @if ($quiz->status === 'published')
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        <a>
                                                            {{ $quiz->creator->name }}
                                                        </a>
                                                        <br />
                                                        <small>
                                                            Tạo lúc
                                                            {{ \Carbon\Carbon::parse($quiz->created_at)->format('d/m/Y') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        {{ $quiz->title }}
                                                    </td>
                                                    <td>
                                                        {{ $quiz->description }}
                                                    </td>
                                                    <td>
                                                        @foreach ($quiz->categories as $category)
                                                            <span class="badge badge-primary">{{ $category->name }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <button type="button" class="badge badge-success"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#showQuestion_{{ $loop->iteration }}">
                                                            Xem bộ câu hỏi
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="showQuestion_{{ $loop->iteration }}"
                                                            tabindex="-1" aria-labelledby="showQuestion"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="showQuestion">Bộ câu
                                                                            hỏi thuộc {{ $quiz->title }}</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Đóng"></button>
                                                                    </div>

                                                                    <!-- Modal Body -->
                                                                    <div class="modal-body">
                                                                        <!-- Form Thêm Câu Hỏi -->
                                                                        <div class="card formAddQuestion"
                                                                            style="width: 100%; display: none;">
                                                                            <div class="card-body">
                                                                                <form action="{{ route('quiz-bank.add') }}" method="post" id="quizForm">
                                                                                    @csrf
                                                                                    <div class="mb-3">
                                                                                        <label for="title" class="form-label">Nhập câu hỏi</label>
                                                                                        <input type="text" class="form-control @error('question_name') is-invalid @enderror" id="title" placeholder="Nhập câu hỏi" name="question_name">
                                                                                        @error('question_name')
                                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                                        @enderror
                                                                                
                                                                                        <label class="form-label">Nhập câu trả lời</label>
                                                                                
                                                                                        <div class="form-check">
                                                                                            <input type="radio" class="form-check-input @error('answer') is-invalid @enderror" name="answer" id="answer_1" value="1">
                                                                                            <label class="form-check-label" for="answer_1">Câu trả lời 1</label>
                                                                                            <input type="text" class="form-control @error('answer_name_1') is-invalid @enderror" id="answer_name_1" placeholder="Nhập câu trả lời 1" name="answer_name_1">
                                                                                            @error('answer_name_1')
                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                            @enderror
                                                                                        </div>
                                                                                
                                                                                        <div class="form-check">
                                                                                            <input type="radio" class="form-check-input @error('answer') is-invalid @enderror" name="answer" id="answer_2" value="2">
                                                                                            <label class="form-check-label" for="answer_2">Câu trả lời 2</label>
                                                                                            <input type="text" class="form-control @error('answer_name_2') is-invalid @enderror" id="answer_name_2" placeholder="Nhập câu trả lời 2" name="answer_name_2">
                                                                                            @error('answer_name_2')
                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                            @enderror
                                                                                        </div>
                                                                                
                                                                                        <div class="form-check">
                                                                                            <input type="radio" class="form-check-input @error('answer') is-invalid @enderror" name="answer" id="answer_3" value="3">
                                                                                            <label class="form-check-label" for="answer_3">Câu trả lời 3</label>
                                                                                            <input type="text" class="form-control @error('answer_name_3') is-invalid @enderror" id="answer_name_3" placeholder="Nhập câu trả lời 3" name="answer_name_3">
                                                                                            @error('answer_name_3')
                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                            @enderror
                                                                                        </div>
                                                                                
                                                                                        <div class="form-check">
                                                                                            <input type="radio" class="form-check-input @error('answer') is-invalid @enderror" name="answer" id="answer_4" value="4">
                                                                                            <label class="form-check-label" for="answer_4">Câu trả lời 4</label>
                                                                                            <input type="text" class="form-control @error('answer_name_4') is-invalid @enderror" id="answer_name_4" placeholder="Nhập câu trả lời 4" name="answer_name_4">
                                                                                            @error('answer_name_4')
                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                            @enderror
                                                                                        </div>
                                                                                    </div>
                                                                                
                                                                                    <input type="hidden" name="quiz_package_id" value="{{ $quiz->id }}">
                                                                                
                                                                                    <button class="btn btn-danger cancelFormQuestion">Hủy</button>
                                                                                    <button type="submit" class="btn btn-primary">Tạo</button>
                                                                                </form>
                                                                                

                                                                            </div>
                                                                        </div>

                                                                        <!-- Table Câu Hỏi -->
                                                                        <div class="table-responsive">
                                                                            <table class="table table-striped table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>STT</th>
                                                                                        <th>Câu hỏi</th>
                                                                                        <th>Tác vụ</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($quiz->quizzes as $question)
                                                                                        <tr>
                                                                                            <td>{{ $loop->iteration }}</td>
                                                                                            <td>{{ $question->question }}
                                                                                            </td>
                                                                                            <td class="text-right">
                                                                                                <a class="btn btn-primary btn-sm"
                                                                                                    title="Xem chi tiết"
                                                                                                    href="#">
                                                                                                    <i
                                                                                                        class="fas fa-eye"></i>
                                                                                                </a>
                                                                                                <a class="btn btn-info btn-sm"
                                                                                                    title="Chỉnh sửa câu hỏi"
                                                                                                    href="#">
                                                                                                    <i
                                                                                                        class="fas fa-pencil-alt"></i>
                                                                                                </a>
                                                                                                <a class="btn btn-danger btn-sm"
                                                                                                    title="Xóa câu hỏi"
                                                                                                    href="#">
                                                                                                    <i
                                                                                                        class="fas fa-trash"></i>
                                                                                                </a>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Modal Footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Đóng</button>
                                                                        <button type="button"
                                                                            class="btn btn-primary addQuestion">Thêm câu
                                                                            hỏi</button>
                                                                    </div>
                                                                    <script>
                                                                        document.addEventListener('DOMContentLoaded', function() {
                                                                            // Lấy tất cả các nút "Thêm câu hỏi"
                                                                            const addQuestionButtons = document.querySelectorAll('.addQuestion');

                                                                            // Lặp qua từng nút "Thêm câu hỏi"
                                                                            addQuestionButtons.forEach(function(button) {
                                                                                button.addEventListener('click', function() {
                                                                                    // Lấy phần tử .formAddQuestion gần nhất trong modal
                                                                                    const formAddQuestion = this.closest('.modal-content').querySelector(
                                                                                        '.formAddQuestion');

                                                                                    if (formAddQuestion) {
                                                                                        console.log('Found .formAddQuestion:', formAddQuestion);
                                                                                        formAddQuestion.style.display = 'block'; // Hiển thị form thêm câu hỏi
                                                                                    } else {
                                                                                        console.log('.formAddQuestion not found.');
                                                                                    }
                                                                                });
                                                                            });

                                                                            // Lấy tất cả các nút "Hủy"
                                                                            const cancelButtons = document.querySelectorAll('.cancelFormQuestion');

                                                                            // Lặp qua từng nút "Hủy"
                                                                            cancelButtons.forEach(function(button) {
                                                                                button.addEventListener('click', function(e) {
                                                                                    e.preventDefault(); // Ngừng hành động mặc định (nếu có)
                                                                                    // Ẩn form khi nhấn Hủy
                                                                                    const formAddQuestion = this.closest('.modal-content').querySelector(
                                                                                        '.formAddQuestion');
                                                                                    if (formAddQuestion) {
                                                                                        formAddQuestion.style.display = 'none'; // Ẩn form
                                                                                    }
                                                                                });
                                                                            });

                                                                            // Lắng nghe sự kiện click trên modal để hiển thị form thêm câu hỏi
                                                                            document.querySelectorAll('#showQuestion').forEach(function(showQuestionButton) {
                                                                                showQuestionButton.addEventListener('click', function() {
                                                                                    // Lấy phần tử .formAddQuestion gần nhất từ phần tử chứa modal
                                                                                    const formAddQuestion = this.closest('.modal-content').querySelector(
                                                                                        '.formAddQuestion');

                                                                                    if (formAddQuestion) {
                                                                                        console.log('Found .formAddQuestion:', formAddQuestion);
                                                                                        formAddQuestion.style.display = 'block'; // Hiển thị form thêm câu hỏi
                                                                                    } else {
                                                                                        console.log('.formAddQuestion not found.');
                                                                                    }
                                                                                });
                                                                            });
                                                                        });
                                                                    </script>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-success text-center">
                                                            {{ $quiz->status === 'published' ? 'Công khai' : '' }}
                                                        </span>
                                                    </td>
                                                    <td class="">
                                                        <a class="btn btn-info btn-sm" title="Chỉnh sửa kho"
                                                            href="#" data-bs-toggle="modal"
                                                            data-bs-target="#edit_quiz_package_{{ $loop->iteration }}">
                                                            <i class="fas fa-pencil-alt">
                                                            </i>
                                                        </a>
                                                        <div class="modal fade"
                                                            id="edit_quiz_package_{{ $loop->iteration }}" tabindex="-1"
                                                            aria-labelledby="edit_quiz_package" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <!-- Header của Modal -->
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="edit_quiz_package">
                                                                            Chỉnh sửa kho {{ $quiz->title }}</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Đóng"></button>
                                                                    </div>
                                                                    <!-- Body của Modal -->
                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <div class="mb-3">
                                                                                <label for="title"
                                                                                    class="form-label">Tên kho</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="title" placeholder="Nhập tên"
                                                                                    name="quiz_name"
                                                                                    value="{{ $quiz->title }}">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="description"
                                                                                    class="form-label">Mô tả</label>
                                                                                <textarea name="quiz_description" class="form-control" id="description" rows="3" placeholder="Nhập mô tả">{{ $quiz->description }}</textarea>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="category"
                                                                                    class="form-label">Danh mục</label>
                                                                                <select id="categories"
                                                                                    name="categories[]"
                                                                                    class="selectpicker form-select"
                                                                                    multiple size="3">
                                                                                    @foreach ($categories as $category)
                                                                                        <option
                                                                                            @foreach ($quiz->categories as $category_quiz)
                                                                                                {{ $category_quiz->id === $category->id ? 'selected' : '' }} @endforeach
                                                                                            value="{{ $category->id }}">
                                                                                            {{ $category->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    $(document).ready(function() {
                                                                                        $('#categories').selectpicker();
                                                                                    });
                                                                                </script>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="status"
                                                                                    class="form-label">Trạng thái</label>
                                                                                <select class="form-select"
                                                                                    id="status">
                                                                                    <option selected value="published">Công
                                                                                        khai
                                                                                    </option>
                                                                                    <option value="private">Ẩn</option>
                                                                                    <option value="closed">Hủy</option>
                                                                                </select>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Footer của Modal -->
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Đóng</button>
                                                                        <button type="button" class="btn btn-primary">Lưu
                                                                            thay đổi</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a onclick="(confirm('Bạn có chắc ẩn kho {{ $quiz->title }} không?') ? window.location.href='{{ route('quiz-bank.hidden') }}' : '')"
                                                            class="btn btn-danger btn-sm" title="Ẩn kho"
                                                            href="javascript:void(0)">
                                                            <i class="fas fa-eye-slash">
                                                            </i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center fs-4 text-danger">Không tồn tại kho quiz!</div>
                @endif
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                @if ($quizBank->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Projects</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Người tạo</th>
                                            <th>Tên kho</th>
                                            <th>Mô tả</th>
                                            <th>Thuộc danh mục</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quizBank as $quiz)
                                            @if ($quiz->status === 'private')
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        <a>
                                                            {{ $quiz->creator->name }}
                                                        </a>
                                                        <br />
                                                        <small>
                                                            Tạo lúc
                                                            {{ \Carbon\Carbon::parse($quiz->created_at)->format('d/m/Y') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        {{ $quiz->title }}
                                                    </td>
                                                    <td>
                                                        {{ $quiz->description }}
                                                    </td>
                                                    <td>
                                                        @foreach ($quiz->categories as $category)
                                                            {{ $category->name }}
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-success text-center">
                                                            {{ $quiz->status === 'private' ? 'Đã ẩn' : '' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <a class="btn btn-primary btn-sm" title="Xem chi tiết"
                                                            href="#">
                                                            <i class="fas fa-street-view">
                                                            </i>
                                                        </a>
                                                        <a class="btn btn-info btn-sm" title="Chỉnh sửa kho"
                                                            href="#">
                                                            <i class="fas fa-pencil-alt">
                                                            </i>
                                                        </a>
                                                        <a class="btn btn-danger btn-sm" title="Ẩn kho" href="#">
                                                            <i class="fas fa-eye-slash">
                                                            </i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                @else
                    <div class="text-center fs-4 text-danger">Không tồn tại kho quiz!</div>
                @endif
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                @if ($quizBank->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Projects</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Người tạo</th>
                                            <th>Tên kho</th>
                                            <th>Mô tả</th>
                                            <th>Thuộc danh mục</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th>Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quizBank as $quiz)
                                            @if ($quiz->status === 'closed')
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        <a>
                                                            {{ $quiz->creator->name }}
                                                        </a>
                                                        <br />
                                                        <small>
                                                            Tạo lúc
                                                            {{ \Carbon\Carbon::parse($quiz->created_at)->format('d/m/Y') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        {{ $quiz->title }}
                                                    </td>
                                                    <td>
                                                        {{ $quiz->description }}
                                                    </td>
                                                    <td>
                                                        @foreach ($quiz->categories as $category)
                                                            {{ $category->name }}
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-success text-center">
                                                            {{ $quiz->status === 'private' ? 'Đã ẩn' : '' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <a class="btn btn-primary btn-sm" title="Xem chi tiết"
                                                            href="#">
                                                            <i class="fas fa-street-view">
                                                            </i>
                                                        </a>
                                                        <a class="btn btn-info btn-sm" title="Chỉnh sửa kho"
                                                            href="#">
                                                            <i class="fas fa-pencil-alt">
                                                            </i>
                                                        </a>
                                                        <a class="btn btn-danger btn-sm" title="Ẩn kho" href="#">
                                                            <i class="fas fa-eye-slash">
                                                            </i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @else
                                                <div class="text-center fs-4 text-danger">Không tồn tại kho quiz!</div>
                                                @php
                                                    break;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                @else
                    <div class="text-center fs-4 text-danger">Không tồn tại kho quiz!</div>
                @endif
            </div>
        </div>
    </section>
@endsection
