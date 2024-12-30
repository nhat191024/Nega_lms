@extends('master')
@section('title', 'Thêm Danh Mục')

@section('content')
<div class="container-fluid">
    <h2 class="my-4">Thêm Danh Mục</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên danh mục:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Danh mục cha:</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">Không có</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">Trạng thái:</label>
            <select name="status" id="status" class="form-control">
                <option value="active">Hiển thị</option>
                <option value="inactive">Ẩn</option>
            </select>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>


</div>
@endsection
