@extends('master')
@section('title', 'Tạo assignment')
@section('content')

<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
''            <form class="form-inline">
''                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
''                    <i class="fa fa-bars"></i>
''                </button>
''            </form>
''            <ul class="navbar-nav ml-auto">
''                <li class="nav-item dropdown no-arrow">
''                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
''                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
''                        <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
''                    </a>
''                </li>
''            </ul>
''        </nav>
''        <!-- End of Topbar -->
''
''        <!-- Begin Page Content -->
''        <div class="container-fluid">
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
                            <label for="creator_id">Creator</label>
                            <select name="creator_id" id="creator_id" class="form-control" >
                                <option value="">Chọn người tạo</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">name</label>
                            <input type="text" name="name" id="title" class="form-control" placeholder="Enter assignment title" >
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter assignment description" rows="3" ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="closed">Closed</option>
                                <option value="published">Published</option>
                                <option value="private">Private</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="level">Level</label>
                            <select name="level" id="level" class="form-control" >
                                <option value="college">Cao đẳng</option>
                                <option value="university">Đại học</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration (in minutes)</label>
                            <input type="time" name="duration" id="duration" class="form-control" placeholder="Enter duration"  value="00:15:00" >
                        </div>

                        <div class="form-group">
                            <label for="totalScore">Total Score</label>
                            <input type="number" name="totalScore" id="totalScore" class="form-control" placeholder="Enter total score" min="0" >
                        </div>

                        <div class="form-group">
                            <label for="specialized">Specialized</label>
                            <input type="text" name="specialized" id="specialized" class="form-control" placeholder="Enter specialization" >
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject" >
                        </div>

                        <div class="form-group">
                            <label for="topic">Topic</label>
                            <input type="text" name="topic" id="topic" class="form-control" placeholder="Enter topic" >
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="datetime-local" name="due_date" id="due_date" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label for="auto_grade">Auto Grade</label>
                            <select name="auto_grade" id="auto_grade" class="form-control" >
                                <option value="1">True</option>
                                <option value="0">False</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class_id">Class Name</label>
                            <select name="class_id" id="class_id" class="form-control" >
                                <option value="">Chọn lớp học</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
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