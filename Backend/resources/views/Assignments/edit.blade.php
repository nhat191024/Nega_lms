@extends('master')
@section('content')
    <div id="content">
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
                        <div class="form-group">
                            <label for="class_id">Class name</label>
                            <select name="class_id" id="class_id" class="form-control" required>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ $class->id === $assignment->class_id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ $assignment->title }}" required>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required>{{ $assignment->description }}</textarea>
                        </div>

                        <!-- Due Date -->
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control"
                                value="{{ $assignment->due_date }}" required>
                        </div>

                        <!-- Auto Grade -->
                        <div class="form-group">
                            <label for="auto_grade">Auto Grade</label>
                            <select name="auto_grade" id="auto_grade" class="form-control" required>
                                <option value="1" {{ $assignment->auto_grade == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $assignment->auto_grade == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Cancel</a>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update Assignment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
