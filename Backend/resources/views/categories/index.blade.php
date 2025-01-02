@extends('master')
@section('content')

@section('title', 'Danh Sách Danh Mục')

@section('content')
    <div class="container-fluid">
        <h2 class="my-4">Danh Sách Danh Mục</h2>

        <a href="{{ route('category.create') }}" class="btn btn-primary mb-3">Thêm Danh Mục</a>

        <div class="table-responsive">
            <table class="table table-bordered">
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
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $category->name }}</td>
                            <td class="text-center">
                                @if ($category->parent)
                                    {{ $category->parent->name }} (ID: {{ $category->parent->id }})
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
                                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                    Sửa
                                </a>
                                <a href="{{ route('category.status', $category->id) }}"
                                    class="btn {{ $category->status === 'active' ? 'btn-danger' : 'btn-success' }} btn-sm">
                                    {{ $category->status === 'active' ? 'Ẩn' : 'Hiển Thị' }}
                                </a>
                                <form action="{{ route('category.destroy', $category->id) }}" method="POST"
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
            </table>
        </div>
    </div>
@endsection

@endsection
