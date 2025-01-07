@extends('master')

@section('title', 'Quản lý kho quiz')

@section('content')
    <div class="content">
        <section class="controller px-3">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="quiz-bank-show-tab" data-bs-toggle="tab" href="#quiz-bank-show" role="tab"
                        aria-controls="quiz-bank-show" aria-selected="true">Kho quiz công khai</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="quiz-bank-hidden-tab" data-bs-toggle="tab" href="#quiz-bank-hidden" role="tab"
                        aria-controls="quiz-bank-hidden" aria-selected="false">Kho quiz đã ẩn</a>
                </li>
            </ul>
            @php
                $indexRender = 0;
            @endphp
            <!-- Tab Content -->
            <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="quiz-bank-show" role="tabpanel" aria-labelledby="quiz-bank-show-tab">
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
                                                <form action="{{ route('quiz-bank.createQuizBank') }}">
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
                                                        <label for="quiz_id_range" class="form-label">Nhập phạm vi
                                                            quiz_id</label>
                                                        <input type="text" class="form-control" id="quiz_id_range"
                                                            name="quiz_id_range"
                                                            placeholder="Nhập phạm vi quiz_id (ví dụ: 1-100)">

                                                        @error('quiz_id_range')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status"
                                                            class="form-label">Xuất bản</label>
                                                        <select name="status" class="form-select" id="status">
                                                            <option selected value="published">Xuất bản</option>
                                                            <option value="closed">Đóng</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status"
                                                            class="form-label">Hiển thị</label>
                                                        <select name="type" class="form-select">
                                                            <option selected value="public">Công khai </option>
                                                            <option value="private">Riêng tư</option>
                                                        </select>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Tạo</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="quiz-published" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> Người tạo</th>
                                                <th>Tên kho</th>
                                                <th>Mô tả</th>
                                                <th>Phạm vi quiz_id</th>
                                                <th>Thuộc danh mục</th>
                                                <th>Bộ câu hỏi</th>
                                                <th class="">Xuất bản</th>
                                                <th class="text-center">Hiển thị</th>
                                                <th style="width: 100px;">Tác vụ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($quizBank as $quiz)
                                                @php
                                                    $indexRender++;
                                                @endphp
                                                @if ($quiz->type === 'public')
                                                    <tr>
                                                        <td> {{ $loop->iteration }} </td>
                                                        <td>
                                                            <a> {{ $quiz->creator->name }} </a>
                                                            <br />
                                                            <small> Tạo lúc {{ \Carbon\Carbon::parse($quiz->created_at)->format('d/m/Y') }} </small>
                                                        </td>
                                                        <td> {{ $quiz->title }} </td>
                                                        <td> {{ $quiz->description }} </td>
                                                        <td> {{ $quiz->quiz_id_range }} </td>
                                                        <td>
                                                            @foreach ($quiz->categories as $category)
                                                                <span class="badge badge-primary">{{ $category->name }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#showQuestion_{{ $indexRender }}"> Xem bộ câu hỏi </button>
                                                            <div class="modal fade" id="showQuestion_{{ $indexRender }}"
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
                                                                            <div class="card formAddQuestion" style="width: 100%; display: none;">
                                                                                <div class="card-body">
                                                                                    <form action="{{ route('quiz-bank.addQuestion') }}" method="post" id="quizForm">
                                                                                        @csrf
                                                                                        <div class="mb-3">
                                                                                            <label for="title" class="form-label">Nhập câu hỏi</label>
                                                                                            <input type="text" class="form-control @error('question_name') is-invalid @enderror" 
                                                                                                id="title" placeholder="Nhập câu hỏi" name="question_name">
                                                                                            @error('question_name')
                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                            @enderror
                                                                                            <label class="form-label">Nhập câu trả lời</label>
                                                                                            @for ($i = 1; $i <= 4; $i++)
                                                                                                <div class="form-check">
                                                                                                    <input type="radio" class="form-check-input @error('anwser') is-invalid @enderror"
                                                                                                        name="anwser" id="anwser_{{ $i }}" value="{{ $i }}">
                                                                                                    <label class="form-check-label" for="anwser_{{ $i }}">Câu trả lời {{ $i }}</label>
                                                                                                    
                                                                                                    <input type="text" class="form-control @error('anwser_name_' . $i) is-invalid @enderror" 
                                                                                                        id="anwser_name_{{ $i }}" placeholder="Nhập câu trả lời {{ $i }}" 
                                                                                                        name="anwser_name_{{ $i }}">
                                                                                                    
                                                                                                    @error('anwser_name_' . $i)
                                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            @endfor
                                                                                        </div>
                                                                            
                                                                                        <input type="hidden" name="quiz_package_id" value="{{ $quiz->id }}">
                                                                            
                                                                                        <button type="button" onclick="cancelFormQuestion()" class="btn btn-danger">Hủy</button>
                                                                                        <button type="submit" class="btn btn-primary">Tạo</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <style>
                                                                                .custom-table .header-table,
                                                                                .custom-table .content-table .main .content {
                                                                                    display: grid;
                                                                                    grid-template-columns: 55px auto 30%;
                                                                                    column-gap: 20px;
                                                                                    row-gap: 10px;
                                                                                    text-align: center
                                                                                }
    
                                                                                .custom-table .content-table .main {
                                                                                    background: whitesmoke;
                                                                                    margin: 5px 0;
                                                                                    border: 1px solid white;
                                                                                    border-radius: 10px;
                                                                                    padding: 10px
                                                                                }
                                                                            </style>
                                                                            <div class="custom-table">
                                                                                <div class="header-table text-start">
                                                                                    <span class="fs-4 fw-bold">STT</span>
                                                                                    <span class="fs-4 fw-bold">Câu hỏi</span>
                                                                                    <span class="fs-4 fw-bold">Tác vụ</span>
                                                                                </div>
                                                                                <div class="content-table">
                                                                                    @foreach ($quiz->quizzes as $index => $question)
                                                                                        @php
                                                                                            $index++;
                                                                                            $indexRender++;
                                                                                        @endphp
                                                                                        <div class="main">
                                                                                            <div class="content mb-3">
                                                                                                <span class="fs-5">{{ $index }}</span>
                                                                                                <span class="fs-5 text-start">{{ $question->question }}</span>
                                                                                                <span class="fs-5 d-flex">
                                                                                                    <span class="me-2" onclick="cardShowQuestion(this)">
                                                                                                        <a class="btn btn-info btn-sm" title="Xem câu hỏi" href="javascript:void(0);"><i class="fas fa-eye"></i></a>
                                                                                                    </span>
                                                                                                    <span class="mx-1" onclick="formEditQuestion(this)">
                                                                                                        <a class="btn btn-primary btn-sm" title="Chỉnh sửa câu hỏi" href="javascript:void(0);"><i class="fas fa-pencil-alt"></i></a>
                                                                                                    </span>
                                                                                                    <span class="ms-2">
                                                                                                        <form class="formDeleteQuestion" action="{{ route('quiz-bank.deleteQuestion') }}" method="post">
                                                                                                            @csrf
                                                                                                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                                                                            <button type="button" onclick="formDeleteQuestion(this)" class="btn btn-danger btn-sm" title="Xóa câu hỏi"><i class="fas fa-trash"></i></button>
                                                                                                        </form>
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            
                                                                                            <div class="editQuestion">
                                                                                                <div class="card formEditQuestion" style="width: 100%; display: none;">
                                                                                                    <div class="card-body">
                                                                                                        <form action="{{ route('quiz-bank.updateQuestion') }}" method="post">
                                                                                                            @csrf
                                                                                                            <div class="mb-3">
                                                                                                                <label for="title" class="form-label">Nhập câu hỏi</label>
                                                                                                                <input type="text" class="form-control @error('question_name') is-invalid @enderror" value="{{ $question->question }}" id="title" placeholder="Nhập câu hỏi" name="question_name">
                                                                                                                @error('question_name')
                                                                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                @enderror
                                                                            
                                                                                                                <label class="form-label">Nhập câu trả lời</label>
                                                                            
                                                                                                                @foreach ($question->choices as $choice)
                                                                                                                    <div class="form-check">
                                                                                                                        <input type="radio" {{ $choice->is_correct === 1 ? 'checked' : '' }} class="form-check-input @error('anwser') is-invalid @enderror"
                                                                                                                            name="anwser" id="anwser_{{ $loop->iteration }}" value="{{ $loop->iteration }}">
                                                                                                                        <label class="form-check-label" for="anwser_{{ $loop->iteration }}">Câu trả lời {{ $loop->iteration }} <span>{{ $choice->is_correct === 1 ? '✅' : '❌' }}</span></label>
                                                                                                                        <input type="text" class="form-control @error('anwser_name_{{ $loop->iteration }}') is-invalid @enderror"
                                                                                                                            id="anwser_name_{{ $loop->iteration }}" value="{{ $choice->choice }}" 
                                                                                                                            placeholder="Nhập câu trả lời {{ $loop->iteration }}" name="anwser_name_{{ $loop->iteration }}">
                                                                                                                        @error('anwser_name_{{ $loop->iteration }}')
                                                                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                        @enderror
                                                                                                                    </div>
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                            <input type="hidden" name="quiz_package_id" value="{{ $question->id }}">
                                                                                                            <div class="text-end">
                                                                                                                <a class="btn btn-danger" href="javascript:void(0);" onclick="cancelFormQuestion()">Hủy</a>
                                                                                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="card cardShowQuestion d-none">
                                                                                                <div class="card-body">
                                                                                                    @foreach ($question->choices as $choice)
                                                                                                        <div class="alert alert-light" role="alert">
                                                                                                            {{ $choice->is_correct === 1 ? '✅ ' : '❌ ' }} {{ $choice->choice }}
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                    <div class="pagination" style="width: max-content; position: relative; left: 50%; transform: translate(-50%);">
                                                                                        <button class="prev" onclick="changePage('prev', this)">Trước</button>
                                                                                        <span class="page-numbers"></span>
                                                                                        <button class="next" onclick="changePage('next', this)">Sau</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        </div>
    
                                                                        <!-- Modal Footer -->
                                                                        <div class="modal-footer text-center">
                                                                            <div>
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                                <button onclick="addQuestion()" type="button" class="btn btn-primary">Thêm câu hỏi</button>
                                                                            </div>
                                                                            <style>
                                                                                .pagination {
                                                                                    text-align: center;
                                                                                    margin-top: 20px;
                                                                                }

                                                                                .page-num {
                                                                                    display: inline-block;
                                                                                    padding: 5px 10px;
                                                                                    margin: 0 5px;
                                                                                    cursor: pointer;
                                                                                    border: 1px solid #ddd;
                                                                                    border-radius: 5px;
                                                                                }

                                                                                .page-num.active {
                                                                                    background-color: #007bff;
                                                                                    color: white;
                                                                                }

                                                                                .page-num:hover {
                                                                                    background-color: #f0f0f0;
                                                                                }

                                                                                .dots {
                                                                                    display: inline-block;
                                                                                    padding: 5px 10px;
                                                                                    margin: 0 5px;
                                                                                }

                                                                                button {
                                                                                    padding: 5px 10px;
                                                                                    margin: 0 5px;
                                                                                    cursor: pointer;
                                                                                }

                                                                                button:disabled {
                                                                                    opacity: 0.5;
                                                                                    cursor: not-allowed;
                                                                                }


                                                                            </style>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-{{ $quiz->status === 'published' ? 'info' : 'warning' }} text-center">
                                                                {{ $quiz->status === 'published' ? 'Đã xuất bản' : 'Đã đóng' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success text-center">
                                                                {{ $quiz->type === 'public' ? 'Công khai' : 'Riêng tư' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-info btn-sm" title="Chỉnh sửa kho"
                                                                href="#" data-bs-toggle="modal"
                                                                data-bs-target="#edit_quiz_package_{{ $indexRender }}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                            </a>
                                                            <div class="modal fade"
                                                                id="edit_quiz_package_{{ $indexRender }}" tabindex="-1"
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
                                                                            <form
                                                                                action="{{ route('quiz-bank.updateQuizBank') }}"
                                                                                method="POST">
                                                                                @csrf
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
                                                                                    <label for="quiz_id_range"
                                                                                        class="form-label">Phạm vi
                                                                                        quiz_id</label>
                                                                                    <input type="text" class="form-control"
                                                                                        id="quiz_id_range"
                                                                                        name="quiz_id_range"
                                                                                        placeholder="Nhập phạm vi quiz_id (ví dụ: 1-100)"
                                                                                        value="{{ $quiz->quiz_id_range }}">
    
                                                                                    @error('quiz_id_range')
                                                                                        <div class="text-danger">
                                                                                            {{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="status" class="form-label">Xuất bản</label>
                                                                                    <select name="status" class="form-select">
                                                                                        <option {{ $quiz->status === 'published' ? 'selected' : '' }} value="published">Xuất </option>
                                                                                        <option {{ $quiz->status === 'closed' ? 'selected' : '' }} value="closed">Đóng</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="type" class="form-label">Hiển thị</label>
                                                                                    <select name="type" class="form-select">
                                                                                        <option {{ $quiz->type === 'public' ? 'selected' : '' }} value="public">Công khai </option>
                                                                                        <option {{ $quiz->type === 'private' ? 'selected' : '' }} value="private">Riêng tư</option>
                                                                                    </select>
                                                                                </div>
                                                                                <input type="hidden" name="quiz_id"
                                                                                    value="{{ $quiz->id }}">
                                                                                <!-- Footer của Modal -->
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-bs-dismiss="modal">Đóng</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary">Lưu
                                                                                        thay đổi</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a onclick="(confirm('Bạn có chắc ẩn kho {{ $quiz->title }} không?') ? window.location.href='{{ route('quiz-bank.hiddenQuizBank', $quiz->id) }}' : '')"
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
                <div class="tab-pane fade" id="quiz-bank-hidden" role="tabpanel" aria-labelledby="quiz-bank-hidden-tab">
                    @if ($quizBank->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="quiz-published" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> Người tạo</th>
                                                <th>Tên kho</th>
                                                <th>Mô tả</th>
                                                <th>Phạm vi quiz_id</th>
                                                <th>Thuộc danh mục</th>
                                                <th>Bộ câu hỏi</th>
                                                <th class="">Xuất bản</th>
                                                <th class="text-center">Hiển thị</th>
                                                <th style="width: 100px;">Tác vụ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($quizBank as $quiz)
                                                
                                                
                                                @if ($quiz->type === 'private')
                                                    <tr>
                                                        <td> {{ $loop->iteration }} </td>
                                                        <td>
                                                            <a> {{ $quiz->creator->name }} </a>
                                                            <br />
                                                            <small> Tạo lúc {{ \Carbon\Carbon::parse($quiz->created_at)->format('d/m/Y') }} </small>
                                                        </td>
                                                        <td> {{ $quiz->title }} </td>
                                                        <td> {{ $quiz->description }} </td>
                                                        <td> {{ $quiz->quiz_id_range }} </td>
                                                        <td>
                                                            @foreach ($quiz->categories as $category)
                                                                <span class="badge badge-primary">{{ $category->name }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#showQuestion_{{ $indexRender }}"> Xem bộ câu hỏi </button>
                                                            <div class="modal fade" id="showQuestion_{{ $indexRender }}"
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
                                                                            <div class="card formAddQuestion" style="width: 100%; display: none;">
                                                                                <div class="card-body">
                                                                                    <form action="{{ route('quiz-bank.addQuestion') }}" method="post" id="quizForm">
                                                                                        @csrf
                                                                                        <div class="mb-3">
                                                                                            <label for="title" class="form-label">Nhập câu hỏi</label>
                                                                                            <input type="text" class="form-control @error('question_name') is-invalid @enderror" 
                                                                                                id="title" placeholder="Nhập câu hỏi" name="question_name">
                                                                                            @error('question_name')
                                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                            @enderror
                                                                                            <label class="form-label">Nhập câu trả lời</label>
                                                                                            @for ($i = 1; $i <= 4; $i++)
                                                                                                <div class="form-check">
                                                                                                    <input type="radio" class="form-check-input @error('anwser') is-invalid @enderror"
                                                                                                        name="anwser" id="anwser_{{ $i }}" value="{{ $i }}">
                                                                                                    <label class="form-check-label" for="anwser_{{ $i }}">Câu trả lời {{ $i }}</label>
                                                                                                    
                                                                                                    <input type="text" class="form-control @error('anwser_name_' . $i) is-invalid @enderror" 
                                                                                                        id="anwser_name_{{ $i }}" placeholder="Nhập câu trả lời {{ $i }}" 
                                                                                                        name="anwser_name_{{ $i }}">
                                                                                                    
                                                                                                    @error('anwser_name_' . $i)
                                                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            @endfor
                                                                                        </div>
                                                                            
                                                                                        <input type="hidden" name="quiz_package_id" value="{{ $quiz->id }}">
                                                                            
                                                                                        <button type="button" onclick="cancelFormQuestion()" class="btn btn-danger">Hủy</button>
                                                                                        <button type="submit" class="btn btn-primary">Tạo</button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <style>
                                                                                .custom-table .header-table,
                                                                                .custom-table .content-table .main .content {
                                                                                    display: grid;
                                                                                    grid-template-columns: 55px auto 30%;
                                                                                    column-gap: 20px;
                                                                                    row-gap: 10px;
                                                                                    text-align: center
                                                                                }
    
                                                                                .custom-table .content-table .main {
                                                                                    background: whitesmoke;
                                                                                    margin: 5px 0;
                                                                                    border: 1px solid white;
                                                                                    border-radius: 10px;
                                                                                    padding: 10px
                                                                                }
                                                                            </style>
                                                                            <div class="custom-table">
                                                                                <div class="header-table text-start">
                                                                                    <span class="fs-4 fw-bold">STT</span>
                                                                                    <span class="fs-4 fw-bold">Câu hỏi</span>
                                                                                    <span class="fs-4 fw-bold">Tác vụ</span>
                                                                                </div>
                                                                                <div class="content-table">
                                                                                    @foreach ($quiz->quizzes as $index => $question)
                                                                                        @php
                                                                                            $index++;
                                                                                            $indexRender++;
                                                                                        @endphp
                                                                                        <div class="main">
                                                                                            <div class="content mb-3">
                                                                                                <span class="fs-5">{{ $index }}</span>
                                                                                                <span class="fs-5 text-start">{{ $question->question }}</span>
                                                                                                <span class="fs-5 d-flex">
                                                                                                    <span class="me-2" onclick="cardShowQuestion(this)">
                                                                                                        <a class="btn btn-info btn-sm" title="Xem câu hỏi" href="javascript:void(0);"><i class="fas fa-eye"></i></a>
                                                                                                    </span>
                                                                                                    <span class="mx-1" onclick="formEditQuestion(this)">
                                                                                                        <a class="btn btn-primary btn-sm" title="Chỉnh sửa câu hỏi" href="javascript:void(0);"><i class="fas fa-pencil-alt"></i></a>
                                                                                                    </span>
                                                                                                    <span class="ms-2">
                                                                                                        <form class="formDeleteQuestion" action="{{ route('quiz-bank.deleteQuestion') }}" method="post">
                                                                                                            @csrf
                                                                                                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                                                                            <button type="button" onclick="formDeleteQuestion(this)" class="btn btn-danger btn-sm" title="Xóa câu hỏi"><i class="fas fa-trash"></i></button>
                                                                                                        </form>
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                            
                                                                                            <div class="editQuestion">
                                                                                                <div class="card formEditQuestion" style="width: 100%; display: none;">
                                                                                                    <div class="card-body">
                                                                                                        <form action="{{ route('quiz-bank.updateQuestion') }}" method="post">
                                                                                                            @csrf
                                                                                                            <div class="mb-3">
                                                                                                                <label for="title" class="form-label">Nhập câu hỏi</label>
                                                                                                                <input type="text" class="form-control @error('question_name') is-invalid @enderror" value="{{ $question->question }}" id="title" placeholder="Nhập câu hỏi" name="question_name">
                                                                                                                @error('question_name')
                                                                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                @enderror
                                                                            
                                                                                                                <label class="form-label">Nhập câu trả lời</label>
                                                                            
                                                                                                                @foreach ($question->choices as $choice)
                                                                                                                    <div class="form-check">
                                                                                                                        <input type="radio" {{ $choice->is_correct === 1 ? 'checked' : '' }} class="form-check-input @error('anwser') is-invalid @enderror"
                                                                                                                            name="anwser" id="anwser_{{ $loop->iteration }}" value="{{ $loop->iteration }}">
                                                                                                                        <label class="form-check-label" for="anwser_{{ $loop->iteration }}">Câu trả lời {{ $loop->iteration }} <span>{{ $choice->is_correct === 1 ? '✅' : '❌' }}</span></label>
                                                                                                                        <input type="text" class="form-control @error('anwser_name_{{ $loop->iteration }}') is-invalid @enderror"
                                                                                                                            id="anwser_name_{{ $loop->iteration }}" value="{{ $choice->choice }}" 
                                                                                                                            placeholder="Nhập câu trả lời {{ $loop->iteration }}" name="anwser_name_{{ $loop->iteration }}">
                                                                                                                        @error('anwser_name_{{ $loop->iteration }}')
                                                                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                                                                        @enderror
                                                                                                                    </div>
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                            <input type="hidden" name="quiz_package_id" value="{{ $question->id }}">
                                                                                                            <div class="text-end">
                                                                                                                <a class="btn btn-danger" href="javascript:void(0);" onclick="cancelFormQuestion()">Hủy</a>
                                                                                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="card cardShowQuestion d-none">
                                                                                                <div class="card-body">
                                                                                                    @foreach ($question->choices as $choice)
                                                                                                        <div class="alert alert-light" role="alert">
                                                                                                            {{ $choice->is_correct === 1 ? '✅ ' : '❌ ' }} {{ $choice->choice }}
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                    <div class="pagination" style="width: max-content; position: relative; left: 50%; transform: translate(-50%);">
                                                                                        <button class="prev" onclick="changePage('prev', this)">Trước</button>
                                                                                        <span class="page-numbers"></span>
                                                                                        <button class="next" onclick="changePage('next', this)">Sau</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        </div>
    
                                                                        <!-- Modal Footer -->
                                                                        <div class="modal-footer text-center">
                                                                            <div>
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-{{ $quiz->status === 'published' ? 'info' : 'warning' }} text-center">
                                                                {{ $quiz->status === 'published' ? 'Đã xuất bản' : 'Đã đóng' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success text-center">
                                                                {{ $quiz->type === 'public' ? 'Công khai' : 'Riêng tư' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-info btn-sm" title="Chỉnh sửa kho"
                                                                href="#" data-bs-toggle="modal"
                                                                data-bs-target="#edit_quiz_package_{{ $indexRender }}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                            </a>
                                                            <div class="modal fade"
                                                                id="edit_quiz_package_{{ $indexRender }}" tabindex="-1"
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
                                                                            <form
                                                                                action="{{ route('quiz-bank.updateQuizBank') }}"
                                                                                method="POST">
                                                                                @csrf
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
                                                                                    <label for="quiz_id_range"
                                                                                        class="form-label">Phạm vi
                                                                                        quiz_id</label>
                                                                                    <input type="text" class="form-control"
                                                                                        id="quiz_id_range"
                                                                                        name="quiz_id_range"
                                                                                        placeholder="Nhập phạm vi quiz_id (ví dụ: 1-100)"
                                                                                        value="{{ $quiz->quiz_id_range }}">
    
                                                                                    @error('quiz_id_range')
                                                                                        <div class="text-danger">
                                                                                            {{ $message }}</div>
                                                                                    @enderror
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="status" class="form-label">Xuất bản</label>
                                                                                    <select name="status" class="form-select">
                                                                                        <option {{ $quiz->status === 'published' ? 'selected' : '' }} value="published">Xuất bản</option>
                                                                                        <option {{ $quiz->status === 'closed' ? 'selected' : '' }} value="closed">Đóng</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="type" class="form-label">Hiển thị</label>
                                                                                    <select name="type" class="form-select">
                                                                                        <option {{ $quiz->type === 'public' ? 'selected' : '' }} value="public">Công khai </option>
                                                                                        <option {{ $quiz->type === 'private' ? 'selected' : '' }} value="private">Riêng tư</option>
                                                                                    </select>
                                                                                </div>
                                                                                <input type="hidden" name="quiz_id"
                                                                                    value="{{ $quiz->id }}">
                                                                                <!-- Footer của Modal -->
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-bs-dismiss="modal">Đóng</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary">Lưu
                                                                                        thay đổi</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a onclick="(confirm('Bạn có chắc hiển thị kho {{ $quiz->title }} không?') ? window.location.href='{{ route('quiz-bank.showQuizBank', $quiz->id) }}' : '')"
                                                                class="btn btn-danger btn-sm" title="Hiển thị kho"
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
            </div>
        </section>
    </div>
    <script>
        function formEditQuestion(button) {
            cancelFormQuestion();
            
            let editQuestion = button.closest('.content').nextElementSibling; 
            let firstFormQuestion = editQuestion.firstElementChild;

            if (firstFormQuestion) {
                firstFormQuestion.style.display = 'block';
            }
        }

        function formDeleteQuestion(button) {
            cancelFormQuestion();

            const formDeleteQuestion = button.closest('.formDeleteQuestion');

            if(confirm('Bạn có chắc chắn muốn xóa không?')) {
                if (formDeleteQuestion) {
                    formDeleteQuestion.submit();
                }
            }
        }


        function cardShowQuestion(button) {
            cancelFormQuestion();

            let showQuestion = button.closest('.content').nextElementSibling;
            if (showQuestion && !showQuestion.classList.contains('cardShowQuestion')) {
                showQuestion = showQuestion.nextElementSibling;
                showQuestion.classList.remove('d-none');
                showQuestion.classList.add('d-block');
            }
            
        }

        function cancelFormQuestion() {
            const formAddQuestion = document.querySelectorAll('.formAddQuestion');
            const formEditQuestion = document.querySelectorAll('.formEditQuestion');
            const cardShowQuestion = document.querySelectorAll('.cardShowQuestion')
            formAddQuestion.forEach(function (formQuestion) {
                formQuestion.style.display = 'none';
            })
            formEditQuestion.forEach(function (formQuestion) {
                formQuestion.style.display = 'none';
            })
            cardShowQuestion.forEach(function (formQuestion) {
                formQuestion.classList.remove('d-block');
                formQuestion.classList.add('d-none');
            })
        }

        function addQuestion() {
            cancelFormQuestion();
            let showFormAddQuestion = document.querySelectorAll('.formAddQuestion');
            showFormAddQuestion.forEach(function(form) {
                form.style.display = 'block';
            })
        }

        // Hàm phân trang cho mỗi bảng
        function paginateTable(contentTable) {
            const items = contentTable.querySelectorAll('.main'); // Lấy tất cả các phần tử câu hỏi
            const itemsPerPage = 3; // Số phần tử mỗi trang
            const totalPages = Math.ceil(items.length / itemsPerPage); // Tổng số trang
            let currentPage = 1; // Trang hiện tại

            // Hiển thị các phần tử của trang hiện tại
            function showPage(page) {
                const startIdx = (page - 1) * itemsPerPage;
                const endIdx = startIdx + itemsPerPage;
                items.forEach((item, index) => {
                    if (index >= startIdx && index < endIdx) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
            

            // Render phân trang (số trang và nút điều hướng)
            function renderPagination() {
                const pageNumbersContainer = contentTable.querySelector('.page-numbers');
                let pagesHtml = '';
                const startPage = Math.max(1, currentPage - Math.floor(3 / 2));
                const endPage = Math.min(totalPages, currentPage + Math.floor(3 / 2));

                if (startPage > 1) {
                    pagesHtml += `<span class="page-num" onclick="alert(1)">1</span>`;
                    // pagesHtml += `<span class="page-num" onclick="goToPage(1)">1</span>`;
                    if (startPage > 2) {
                        pagesHtml += `<span class="dots">...</span>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    if (i === currentPage) {
                        pagesHtml += `<span class="page-num active">${i}</span>`;
                    } else {
                        pagesHtml += `<span class="page-num" onclick="goToPage(${i})">${i}</span>`;
                    }
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        pagesHtml += `<span class="dots">...</span>`;
                    }
                    pagesHtml += `<span class="page-num" onclick="goToPage(${totalPages})">${totalPages}</span>`;
                }

                pageNumbersContainer.innerHTML = pagesHtml;
            }
            // Initial rendering
            renderPagination();
            showPage(currentPage);

            

            // Chuyển đến trang tiếp theo hoặc trang trước
            function changePage(direction) {
                if (direction === 'prev' && currentPage > 1) {
                    currentPage--;
                } else if (direction === 'next' && currentPage < totalPages) {
                    currentPage++;
                }
                renderPagination();
                showPage(currentPage);
            }

            // Attach event listeners to prev/next buttons
            const prevButton = contentTable.querySelector('.prev');
            const nextButton = contentTable.querySelector('.next');

            prevButton.addEventListener('click', () => changePage('prev'));
            nextButton.addEventListener('click', () => changePage('next'));

            // Chuyển đến trang cụ thể
            function goToPage(page) {
                console.log("Go to page:", page); 
                if (page < 1 || page > totalPages) return; // Kiểm tra nếu trang hợp lệ
                currentPage = page;
                renderPagination();
                showPage(currentPage);
            }
        }

        // Chạy khi trang web tải xong, áp dụng phân trang cho mỗi bảng
        window.onload = () => {
            const contentTables = document.querySelectorAll('.content-table');
            contentTables.forEach(table => {
                paginateTable(table); // Gọi hàm phân trang cho mỗi bảng
            });
        };
        var myModal = document.querySelectorAll('.modal');
        myModal.forEach(function(modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                cancelFormQuestion();
            });
        })
        
    </script>
@endsection
