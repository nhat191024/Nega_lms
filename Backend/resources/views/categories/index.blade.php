@extends('master')
@section('title', 'Quản lý Danh Mục')
@section('content')
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Quản lý Danh Mục</h1>
            <div class="card shadow my-4">
                <div class="card-header py-3">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <span class="text">Thêm Danh Mục</span>
                    </a>
                </div>
                @if ($categories->isNotEmpty())
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered class-table" id="table-categories" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên</th>
                                        <th class="text-center">Danh Mục Cha</th>
                                        <th class="text-center">Trạng Thái</th>
                                        <th class="text-center">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $index => $category)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $category->name }}</td>
                                            <td class="text-center">
                                                @if ($category->parent)
                                                    {{ $category->parent->name }}
                                                @else
                                                    Không có danh mục cha
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $category->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $category->status === 'active' ? 'Hiển Thị' : 'Ẩn' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                                    Sửa
                                                </a>
                                                <a href="{{ route('categories.status', $category->id) }}"
                                                    class="btn {{ $category->status === 'active' ? 'btn-danger' : 'btn-success' }} btn-sm">
                                                    {{ $category->status === 'active' ? 'Ẩn' : 'Hiển Thị' }}
                                                </a>
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                    style="display: inline-block;"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên</th>
                                        <th class="text-center">Danh Mục Cha</th>
                                        <th class="text-center">Trạng Thái</th>
                                        <th class="text-center">Hành Động</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body">
                        <p class="text-center">Không có danh mục nào</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection
