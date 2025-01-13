@extends('master')
@section('title', 'Chỉnh Sửa Danh Mục')

@section('content')
    <div id="content">
        <div class="container-fluid">
            <h2 class="my-4">Chỉnh Sửa Danh Mục</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Tên danh mục:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="parent_id">Danh mục cha:</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">Không có</option>
                        @foreach ($categories as $parent)
                            <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Trạng thái:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </form>
        </div>
    </div>

@endsection
