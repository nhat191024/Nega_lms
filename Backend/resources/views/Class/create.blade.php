@extends('master')

@section('title')
    <title>Admin - Classes/Create</title>
@endsection

@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Create Class</h1>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Create Class</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="content">
                                <div class="d-flex justify-content-center align-items-center"
                                    style="height: 100%; padding-top: 56px;">
                                    <div class="card w-50">
                                        <div class="card-body">
                                            <h2 class="card-title">Create New Class</h2>
                                            <br>
                                            <form action="{{ route('classes.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <!-- Class Name -->
                                                <div class="mb-3">
                                                    <label for="class_name" class="form-label">Class Name</label>
                                                    <input type="text" class="form-control" id="class_name" name="class_name" value="{{ old('class_name') }}" required>
                                                    @error('class_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Class Description -->
                                                <div class="mb-3">
                                                    <label for="class_description" class="form-label">Class Description</label>
                                                    <textarea class="form-control" id="class_description" name="class_description" required>{{ old('class_description') }}</textarea>
                                                    @error('class_description')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Teacher ID -->
                                                <div class="mb-3">
                                                    <label for="teacher_id" class="form-label">Teacher</label>
                                                    <select class="form-control" id="teacher_id" name="teacher_id" required>
                                                        <option value="">Select Teacher</option>
                                                        @foreach($classes as $teacher)
                                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('teacher_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Create Button -->
                                                <button type="submit" class="btn btn-primary">Create Class</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2020</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
@endsection
