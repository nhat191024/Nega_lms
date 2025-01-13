@extends('master')

@section('title', 'Bảng điều khiển')

@section('content')
<div id="content">
    <div class="container-fluid my-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Bảng điều khiển</h1>
        </div>

        <div class="row mb-4">
            @php
                $cards = [
                    ['label' => 'Tổng số bài tập', 'count' => $assignments ? count($assignments) : 0, 'icon' => 'calendar', 'color' => 'primary'],
                    ['label' => 'Tổng Số học sinh tham gia', 'count' => $students ? count($students) : 0, 'icon' => 'user', 'color' => 'success'],
                    ['label' => 'Tổng số câu hỏi', 'count' => $questions ? count($questions) : 0, 'icon' => 'clipboard-list', 'color' => 'info'],
                    ['label' => 'Tổng số lớp học', 'count' => $classes ? count($classes) : 0, 'icon' => 'comments', 'color' => 'warning']
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center px-3">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                        {{ $card['label'] }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {!! $card['count'] !!}
                                    </div>
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
    </div>

    <div class="container-fluid mb-4">
        <h1 class="h4 mb-4 text-dark">Thống Kê Điểm Số và Thành Tích Của Lớp Học</h1>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Lọc Thông Tin</h5>
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="classSelect" class="form-label">Chọn Lớp Học:</label> <br>
                            <select id="classSelect" class="form-select selectpicker" data-live-search="true">
                                <option value="">Chọn lớp</option>
                                @foreach ($classes as $class)
                                    <option value="{{$class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var classSelect = document.getElementById('classSelect');
                                if (classSelect) {
                                    new bootstrap.Select(classSelect); 
                                }
                            });
                        </script>
                        <div class="col-md-6 mb-3">
                            <label for="classAverageScore" class="form-label">Điểm Trung Bình Lớp:</label>
                            <input type="text" id="classAverageScore" class="form-control" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
        <!-- Biểu đồ điểm số -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Biểu Đồ Điểm Trung Bình Của Học Sinh</h5>
                <canvas id="scoreChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="container-fluid mb-4">
        <h1 class="h4 mb-4 text-dark">Phân Tích Hành Vi Học Sinh</h1>
    
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Lọc Hành Vi Học Sinh</h5>
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="classSelect" class="form-label">Chọn lớp:</label> <br>
                            <select id="classSelect" class="form-select selectpicker" data-live-search="true">
                                <option value="" disabled selected>Chọn Học Sinh...</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="userSelect" class="form-label">Chọn Học Sinh:</label> <br>
                            <select id="userSelect" class="form-select selectpicker" data-live-search="true">
                                <option value="" disabled selected>Chọn Học Sinh...</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var userSelect = document.getElementById('userSelect');
                                if (userSelect) {
                                    new bootstrap.Select(userSelect); 
                                }
                            });
                        </script>
                        
                        <div class="col-md-4 mb-3">
                            <label for="timeRange" class="form-label">Khoảng Thời Gian:</label>
                            <select id="timeRange" class="form-select">
                                <option value="7">7 ngày qua</option>
                                <option value="30">30 ngày qua</option>
                                <option value="90">90 ngày qua</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <button type="button" class="btn btn-primary w-100" id="filterButton">Lọc Dữ Liệu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
        <div id="analyticsResult">
            <div class="row text-center mb-4">
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5 class="card-title">Tổng Số Lớp Tham Gia</h5>
                        <h3 id="totalClasses" class="text-primary">0</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5 class="card-title">Tỷ Lệ Hoàn Thành Bài Tập</h5>
                        <h3 id="assignmentCompletion" class="text-success">0%</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5 class="card-title">Tương Tác Thông Báo</h5>
                        <h3 id="totalInteractions" class="text-warning">0</h3>
                    </div>
                </div>
            </div>
    
            <!-- Biểu đồ hoàn thành -->
            {{-- <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Biểu Đồ Hoàn Thành Bài Tập</h5>
                    <canvas id="completionChart" height="100"></canvas>
                </div>
            </div> --}}
    
            <!-- Chi Tiết Hành Vi -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Chi Tiết Hành Vi</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Loại Hoạt Động</th>
                                <th>Lời nhắn</th>
                                <th>Thời Gian Gần Nhất</th>
                            </tr>
                        </thead>
                        <tbody id="userActivityTable">
                            <tr>
                                <td colspan="4" class="text-center">Chưa có dữ liệu</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CSS cho các biểu đồ -->
    <style>
        #scoreChart {
            width: 100% !important;  /* Đặt chiều rộng tự động */
            height: 400px !important; /* Kích thước chiều cao phù hợp */
        }
    </style>
    
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
    document.getElementById('filterButton').addEventListener('click', function() {
        const userId = document.getElementById('userSelect').value;
        const timeRange = document.getElementById('timeRange').value;

        if (userId) {
            fetch(`/api/user-analytics/classes/${userId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalClasses').textContent = data.classes.length;
                })
                .catch(error => {
                    console.error("Lỗi lấy dữ liệu lớp học:", error);
                });

            fetch(`/api/user-analytics/progress/${userId}`)
                .then(response => response.json())
                .then(data => {
                    const assignmentCompletion = data.assignments.total_score / data.assignments.total_submitted;
                    document.getElementById('assignmentCompletion').textContent = (Math.min(assignmentCompletion, 1) * 100).toFixed(2) + '%';
                    // Vẽ biểu đồ hoàn thành
                    const ctx = document.getElementById('completionChart').getContext('2d');
                    const completionChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Hoàn thành', 'Chưa hoàn thành'],
                            datasets: [{
                                label: 'Tỷ lệ hoàn thành bài tập',
                                data: [completionRate, 100 - completionRate],
                                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: true, ticks: { stepSize: 10 } },
                                x: { title: { display: true, text: 'Trạng thái' }}
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error("Lỗi lấy dữ liệu tiến trình:", error);
                });

            fetch(`/api/user-analytics/interactions/${userId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalInteractions').textContent = data.notifications;
                    const activityTable = document.getElementById('userActivityTable');
                    activityTable.innerHTML = ''; 

                    if (data.recent_comments.length > 0) {
                        data.recent_comments.forEach(function(comment, index) {
                            const row = activityTable.insertRow();
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>Bình luận</td>
                                <td>${comment.content}</td>
                                <td>${comment.created_at}</td>
                            `;
                        });
                    } else {
                        activityTable.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center">Chưa có dữ liệu hành vi</td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error("Lỗi lấy dữ liệu tương tác:", error);
                });
        } else {
            alert('Vui lòng chọn người dùng');
        }
    });

    // function renderChart(chartData) {
    //     const ctx = document.getElementById('completionChart').getContext('2d');
    //     new Chart(ctx, {
    //         type: 'line',
    //         data: {
    //             labels: chartData.labels,
    //             datasets: [{
    //                 label: 'Tỷ lệ hoàn thành',
    //                 data: chartData.data,
    //                 borderColor: 'rgba(75, 192, 192, 1)',
    //                 tension: 0.1,
    //                 fill: false,
    //             }]
    //         },
    //         options: {
    //             responsive: true,
    //             scales: {
    //                 x: { title: { display: true, text: 'Ngày' }},
    //                 y: { title: { display: true, text: 'Tỷ lệ hoàn thành (%)' }}
    //             }
    //         }
    //     });
    // }

    // function renderTable(activities) {
    //     const tbody = document.getElementById('userActivityTable');
    //     tbody.innerHTML = '';

    //     if (!activities || activities.length === 0) {
    //         tbody.innerHTML = '<tr><td colspan="4" class="text-center">Chưa có dữ liệu</td></tr>';
    //         return;
    //     }

    //     activities.forEach((activity, index) => {
    //         tbody.innerHTML += `
    //             <tr>
    //                 <td>${index + 1}</td>
    //                 <td>${activity.type}</td>
    //                 <td>${activity.count}</td>
    //                 <td>${activity.last_time}</td>
    //             </tr>
    //         `;
    //     });
    // }

    let scoreChart;
    const ctx = document.getElementById('scoreChart').getContext('2d');
    
    function createChart(labels, data) {
        if (scoreChart) {
            scoreChart.destroy(); 
        }
        scoreChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Điểm trung bình',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        max: 10, 
                        ticks: { stepSize: 1 }, 
                        title: { display: true, text: 'Điểm trung bình' }
                    },
                    x: { title: { display: true, text: 'Học sinh' }}
                }
            }
        });
    }

    fetch('/api/classes')
        .then(response => response.json())
        .then(classes => {
            const classSelect = document.getElementById('classSelect');
            classes.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.name;
                classSelect.appendChild(option);
            });
        });

    document.getElementById('classSelect').addEventListener('change', function () {
        const classId = this.value;
        if (classId) {
            fetch(`/api/scores-achievements/${classId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const labels = [];
                    const scores = [];

                    Object.keys(data).forEach(function(key) {
                        if (key !== 'class_average_score') {
                            const student = data[key];
                            labels.push(`${student.student_name}`);
                            scores.push(student.average_score);
                        }
                    });

                    const classAverageScore = data.class_average_score;
                    document.getElementById('classAverageScore').value = `${classAverageScore !== 0 ? classAverageScore : 'Chưa có dữ liệu'} điểm`;

                    createChart(labels, scores);
                });

        }
    });
</script>

    
@endsection
