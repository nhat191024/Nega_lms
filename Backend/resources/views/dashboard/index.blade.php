@extends('master')

@section('title', 'Bảng điều khiển')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Content Row -->
        <div class="row mb-4">
            @foreach ([['label' => 'Tổng số bài tập', 'count' => $assignments ? count($assignments) : 0, 'icon' => 'calendar', 'color' => 'primary'], ['label' => 'Số học sinh tham gia', 'count' => $students ? count($students) : 0, 'icon' => 'user', 'color' => 'success'], ['label' => 'Số bài đã hoàn thành', 'count' => $submissions ? count($submissions) : 0, 'icon' => 'clipboard-list', 'color' => 'info'], ['label' => 'Tổng số giảng viên', 'count' => $teachers ? count($teachers) : 0, 'icon' => 'comments', 'color' => 'warning']] as $card)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center px-3">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                        {{ $card['label'] }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $card['count'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-{{ $card['icon'] }} fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mb-4">
            <canvas id="quizParticipationChart"></canvas>
            <script>
                var ctx = document.getElementById('quizParticipationChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($dates) !!},
                        datasets: [{
                            label: 'Tổng Số Người Tham Gia',
                            data: {!! json_encode($participantsCount) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Số Người Tham Gia Quiz Theo Thời Gian',
                                font: {
                                    size: 24
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Ngày'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Số Người Tham Gia'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>

        <!-- Average Score of Assignments -->
        <div class="row mb-4">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Điểm Trung Bình Của Mỗi Bài Quiz đã hoàn thành</h4>
                    </div>
                    <div class="card-body">
                        <table id="averageScoreTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Bài Quiz</th>
                                    <th>Tổng Điểm</th>
                                    <th>Điểm Trung Bình</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($averageScoreForAssignments as $score)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $score['assignment_name'] }}</td>
                                        <td>{{ number_format($score['total_score'], 2) }}</td>
                                        <td>{{ number_format($score['average_score'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Submissions -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Top học sinh có điểm cao nhất</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="topSubmissionsTable" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên học sinh</th>
                                        <th>Tên bài tập</th>
                                        <th>Điểm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($submissions->take(5) as $submission)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $submission->student->name }}</td>
                                            <td>{{ $submission->assignment->name }}</td>
                                            <td>{{ $submission->total_score }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Students -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Học sinh đăng ký mới</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="latestStudentsTable" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên học sinh</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Lớp học</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students->take(5) as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone ?? 'Chưa có số điện thoại' }}</td>
                                            <td>{{ $student->enrollments->first()->class->name ?? 'Chưa có lớp' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#averageScoreTable').DataTable();
            $('#topSubmissionsTable').DataTable();
            $('#latestStudentsTable').DataTable();
        });
    </script>
@endsection
