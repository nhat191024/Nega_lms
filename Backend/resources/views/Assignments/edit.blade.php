@extends('master')
@section('content')

<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <form class="form-inline">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
            </form>

            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search..."
                        aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- User Information -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                        <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Edit Assignment</h1>
            <p class="mb-4">Use this form to edit an existing assignment.</p>

            <!-- Edit Assignment Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('assignments.update', $assignment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Select Class -->
                       <!-- Class ID -->
<div class="form-group">
    <label for="class_id">Class Name</label>
    <select name="class_id" id="class_id" class="form-control" required>
        @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ ($class->id === $assignment->class_id) ? 'selected' : '' }}>
                {{ $class->class_name }}
            </option>
        @endforeach
    </select>
</div>

<!-- Creator ID -->
<div class="form-group">
    <label for="creator_id">Creator</label>
    <select name="creator_id" id="creator_id" class="form-control" required>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ ($user->id === $assignment->creator_id) ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div>

<!-- Name -->
<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ $assignment->name }}" required>
</div>

<!-- Description -->
<div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" id="description" class="form-control" rows="3" required>{{ $assignment->description }}</textarea>
</div>

<!-- Status -->
<div class="form-group">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control" required>
        <option value="published" {{ $assignment->status == 'published' ? 'selected' : '' }}>Published</option>
        <option value="ongoing" {{ $assignment->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
    </select>
</div>

<!-- Level -->
<div class="form-group">
    <label for="level">Level</label>
    <select name="level" id="level" class="form-control" required>
        <option value="college" {{ $assignment->level == 'college' ? 'selected' : '' }}>Cao đẳng</option>
        <option value="university" {{ $assignment->level == 'university' ? 'selected' : '' }}>Đại học</option>
    </select>
</div>

<!-- Duration -->
<div class="form-group">
    <label for="duration">Duration (in minutes)</label>
    <input type="number" name="duration" id="duration" class="form-control" value="{{ $assignment->duration }}" min="1" required>
</div>

<!-- Total Score -->
<div class="form-group">
    <label for="totalScore">Total Score</label>
    <input type="number" name="totalScore" id="totalScore" class="form-control" value="{{ $assignment->totalScore }}" min="0" required>
</div>

<!-- Specialized -->
<div class="form-group">
    <label for="specialized">Specialized</label>
    <input type="text" name="specialized" id="specialized" class="form-control" value="{{ $assignment->specialized }}" required>
</div>

<!-- Subject -->
<div class="form-group">
    <label for="subject">Subject</label>
    <input type="text" name="subject" id="subject" class="form-control" value="{{ $assignment->subject }}" required>
</div>

<!-- Topic -->
<div class="form-group">
    <label for="topic">Topic</label>
    <input type="text" name="topic" id="topic" class="form-control" value="{{ $assignment->topic }}" required>
</div>

<!-- Start Date -->
<div class="form-group">
    <label for="start_date">Start Date</label>
    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $assignment->start_date }}" required>
</div>

<!-- Due Date -->
<div class="form-group">
    <label for="due_date">Due Date</label>
    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $assignment->due_date }}" required>
</div>

<!-- Auto Grade -->
<div class="form-group">
    <label for="auto_grade">Auto Grade</label>
    <select name="auto_grade" id="auto_grade" class="form-control" required>
        <option value="1" {{ $assignment->auto_grade == 1 ? 'selected' : '' }}>True</option>
        <option value="0" {{ $assignment->auto_grade == 0 ? 'selected' : '' }}>False</option>
    </select>
</div>

                        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Cancel</a>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update Assignment</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
</div>

@endsection
