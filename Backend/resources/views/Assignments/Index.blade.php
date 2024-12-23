@extends('master')
@section('content')
    <div id="content">
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý bài tập</h1>

            <!-- Kiểm tra xem có bài tập nào không -->
            @if ($assignments->isNotEmpty())
                {{-- @foreach ($assignmentsGroupBy as $classId => $assignments) --}}
                <div class="card shadow my-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">

                        <a href="{{ route('assignments.create') }}" class="btn btn-primary">Tạo bài tập</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered class-table" data-class-table="table-users" id="table-users"
                                width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tạo bởi</th>
                                        <th class="text-center">Tiêu đề</th>
                                        <th class="text-center">Mô tả</th>
                                        <th class="text-center">trạng thái</th>
                                        <th class="text-center">Cấp bậc</th>
                                        <th class="text-center">tổng điểm</th>
                                        <th class="text-center">Chuyên môn</th>
                                        <th class="text-center">Môn học</th>
                                        <th class="text-center">Chủ đề học tập</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assignments as $index => $assignment)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{ $assignment->creator->name ?? 'Không xác định' }}
                                            </td>
                                            <td class="text-center">{{ $assignment->title }}</td>
                                            <td class="text-center">{{ $assignment->description }}</td>
                                            <td class="text-center">{{ $assignment->status }}</td>
                                            <td class="text-center">{{ $assignment->level }}</td>
                                            <td class="text-center">{{ $assignment->totalScore }}</td>
                                            <td class="text-center">{{ $assignment->specialized }}</td>
                                            <td class="text-center">{{ $assignment->subject }}</td>
                                            <td class="text-center">{{ $assignment->topic }}</td>
                                            <td class="text-center">{{ $assignment->status }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('assignments.edit', $assignment->id) }}"
                                                    class="btn btn-warning btn-sm">Sửa</a>
                                                <a href="{{ route('assignments.assignments.visibility', ['id' => $assignment->id]) }}"
                                                    class="btn {{ $assignment->status == 'published' ? 'btn-danger' : 'btn-success' }} btn-sm">
                                                    @if ($assignment->status == 'published')
                                                        Ẩn
                                                    @else
                                                        Hiển thị
                                                    @endif
                                                </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tạo bởi</th>
                                        <th class="text-center">Tiêu đề</th>
                                        <th class="text-center">Mô tả</th>
                                        <th class="text-center">trạng thái</th>
                                        <th class="text-center">Cấp bậc</th>
                                        <th class="text-center">tổng điểm</th>
                                        <th class="text-center">Chuyên môn</th>
                                        <th class="text-center">Môn học</th>
                                        <th class="text-center">Chủ đề học tập</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
                {{-- @endforeach --}}
            @else
                <p>Chưa có bài tập nào.</p>
            @endif
        </div>
    </div>
@endsection
