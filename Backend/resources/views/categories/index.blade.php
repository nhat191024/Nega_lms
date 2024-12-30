@extends('master')
@section('content')

@section('title', 'Danh Sách Danh Mục')

@section('content')
<div class="container-fluid">
    <h2 class="my-4">Danh Sách Danh Mục</h2>

    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Thêm Danh Mục</a>

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
                            <div class="accordion" id="accordionParent{{ $category->id }}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingParent{{ $category->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseParent{{ $category->id }}"
                                                aria-expanded="false" aria-controls="collapseParent{{ $category->id }}">
                                            {{ $category->name }}
                                        </button>
                                    </h2>
                                    <div id="collapseParent{{ $category->id }}" class="accordion-collapse collapse"
                                         aria-labelledby="headingParent{{ $category->id }}"
                                         data-bs-parent="#accordionParent{{ $category->id }}">
                                        <div class="accordion-body">


                                            <!-- Danh sách các danh mục con -->
                                            @if ($category->children->isNotEmpty())
                                                <p><strong>Danh mục con:</strong></p>
                                                <ul>
                                                    @foreach ($category->children as $child)
                                                        <li>{{ $child->name }} (ID: {{ $child->id }})</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>Không có danh mục con.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $category->status ? 'Hiển Thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                Sửa
                            </a>
                            <a href="{{ route('categories.toggleStatus', $category->id) }}"
                               class="btn {{ $category->status ? 'btn-danger' : 'btn-success' }} btn-sm">
                                {{ $category->status ? 'Ẩn' : 'Hiển Thị' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@endsection
