@extends('master')
@section('content')
    <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Assignment</h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ route('assignments.create') }}" class="btn btn-primary">Create</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered class-table" data-class-table="table-assignment" id="table-assignment"
                            width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Class name</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>due_date</th>
                                    <th>auto_grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignments)
                                    <tr>
                                        <td>{{ $assignments->class->class_name }}</td>
                                        <td>{{ $assignments->title }}</td>
                                        <td>{{ $assignments->description }}</td>
                                        <td>{{ $assignments->due_date }}</td>
                                        <td>{{ $assignments->auto_grade }}</td>
                                        <td>
                                            <a href="/assignments/edit/{{ $assignments->id }}"
                                                class="btn btn-warning btn-sm">Sửa</a>
                                            <form action="{{ route('assignments.destroy', $assignments->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có đồng ý xóa không?')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>class_name</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
@endsection
