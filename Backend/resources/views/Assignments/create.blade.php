@extends('master')
@section('title', 'Tạo assignment')
@section('content')

<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        {{-- <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <form class="form-inline">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
            </form>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                        <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                    </a>
                </li>
            </ul>
        </nav> --}}
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Create New Assignment</h1>
            <p class="mb-4">Use this form to create a new assignment.</p>

            <!-- Form Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">New Assignment Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('assignments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="creator_id">Người tạo</label>
                            <select name="creator_id" id="creator_id" class="form-control" >
                                <option value="">Chọn người tạo</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter assignment title" >
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter assignment description" rows="3" ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" id="status" class="form-control">
                                <option value="closed">Closed</option>
                                <option value="published">Published</option>
                                <option value="private">Private</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="level">Cấp độ</label>
                            <select name="level" id="level" class="form-control" >
                                <option value="Cao đẳng">Cao đẳng</option>
                                <option value="Đại học">Đại học</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="totalScore">Tổng điểm</label>
                            <input type="number" name="totalScore" id="totalScore" class="form-control" placeholder="Enter total score" min="0" >
                        </div>

                        <div class="form-group">
                            <label for="specialized">Chuyên môn</label>
                            <input type="text" name="specialized" id="specialized" class="form-control" placeholder="Enter specialization" >
                        </div>

                        <div class="form-group">
                            <label for="subject">Môn học</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject" >
                        </div>

                        <div class="form-group">
                            <label for="topic">Chủ đề học tập</label>
                            <input type="text" name="topic" id="topic" class="form-control" placeholder="Enter topic" >
                        </div>



                        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Assignment</button>

                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
</div>

@endsection
