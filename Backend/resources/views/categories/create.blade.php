@extends('master')
@section('title', 'Thêm Danh Mục')

@section('content')
<div class="container-fluid">
    <h2 class="my-4">Thêm Danh Mục</h2>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('category.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên danh mục:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Danh mục cha:</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">Không có</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">Trạng thái:</label>
            <select name="status" id="status" class="form-control">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hiển thị</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <a href="{{ route('category.index') }}" class="btn btn-secondary">Hủy</a>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
</div>
@endsection
