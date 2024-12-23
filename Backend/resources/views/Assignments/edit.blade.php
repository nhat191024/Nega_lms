@extends('master')
@section('content')
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Edit Assignment</h1>


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
                        <!-- Creator ID -->
                        <div class="form-group">
                            <label for="creator_id">Người tạo</label>
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
                            <label for="name">Tiêu đề</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $assignment->title }}" required>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required>{{ $assignment->description }}</textarea>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="published" {{ $assignment->status == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="ongoing" {{ $assignment->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            </select>
                        </div>

                        <!-- Level -->
                        <div class="form-group">
                            <label for="level">Cấp độ</label>
                            <select name="level" id="level" class="form-control" required>
                                <option value="Cao đẳng" {{ $assignment->level == 'college' ? 'selected' : '' }}>Cao đẳng</option>
                                <option value="Đại học" {{ $assignment->level == 'university' ? 'selected' : '' }}>Đại học</option>
                            </select>
                        </div>

                        <!-- Total Score -->
                        <div class="form-group">
                            <label for="totalScore">Tổng điểm</label>
                            <input type="number" name="totalScore" id="totalScore" class="form-control" value="{{ $assignment->totalScore }}" min="0" required>
                        </div>

                        <!-- Specialized -->
                        <div class="form-group">
                            <label for="specialized">Chuyên môn</label>
                            <input type="text" name="specialized" id="specialized" class="form-control" value="{{ $assignment->specialized }}" required>
                        </div>

                        <!-- Subject -->
                        <div class="form-group">
                            <label for="subject">Môn học</label>
                            <input type="text" name="subject" id="subject" class="form-control" value="{{ $assignment->subject }}" required>
                        </div>

                        <!-- Topic -->
                        <div class="form-group">
                            <label for="topic">Chủ đề học tập</label>
                            <input type="text" name="topic" id="topic" class="form-control" value="{{ $assignment->topic }}" required>
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
@endsection
