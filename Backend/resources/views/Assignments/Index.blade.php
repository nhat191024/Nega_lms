@extends('master')
@section('content')
<div id="content">
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý bài tập</h1>

            <!-- Kiểm tra xem có bài tập nào không -->
            @if ($assignmentsGroupBy->isNotEmpty())
                @foreach ($assignmentsGroupBy as $classId => $assignments)
                    <div class="card shadow my-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Bài tập: {{ $assignments->first()->class->class_name }}</h6>
                            <a href="{{ route('assignments.create') }}" class="btn btn-primary">Tạo bài tập</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered class-table" 
                                       data-class-table="table-{{ Str::slug($assignments->first()->class->class_name) }}"
                                       id="table-{{ Str::slug($assignments->first()->class->class_name) }}" 
                                       width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tên lớp</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Mô tả</th>
                                            <th class="text-center">status</th>
                                            <th class="text-center">level</th>
                                            <th class="text-center">duration</th>
                                            <th class="text-center">totalScore</th>
                                            <th class="text-center">specialized</th>
                                            <th class="text-center">subject</th>
                                            <th class="text-center">topic</th>
                                            <th class="text-center">start_date</th>
                                            <th class="text-center">Ngày hết hạn</th>
                                            <th class="text-center">Tự động chấm điểm</th>
                                            <th class="text-center">Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignments as $index => $assignment)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $assignment->class->class_name }}</td>
                                                <td class="text-center">{{ $assignment->title }}</td>
                                                <td class="text-center">{{ $assignment->description }}</td>
                                                td class="text-center">{{ $assignment->status }}</td>
                                                <td class="text-center">{{ $assignment->due_date }}</td>
                                                <td class="text-center">{{ $assignment->auto_grade }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                                    <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa bài tập này?')">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tên lớp</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Mô tả</th>
                                            <th class="text-center">status</th>
                                            <th class="text-center">level</th>
                                            <th class="text-center">duration</th>
                                            <th class="text-center">totalScore</th>
                                            <th class="text-center">specialized</th>
                                            <th class="text-center">subject</th>
                                            <th class="text-center">topic</th>
                                            <th class="text-center">start_date</th>
                                            <th class="text-center">Ngày hết hạn</th>
                                            <th class="text-center">Tự động chấm điểm</th>
                                            <th class="text-center">Tác vụ</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Chưa có bài tập nào.</p>
            @endif
        </div>
    </div>
@endsection
