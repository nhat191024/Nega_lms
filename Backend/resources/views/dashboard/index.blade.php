@extends('master')

@section('title', 'Bảng điều khiển')

@section('content')
    <div id="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Bảng điều khiển</h1>
            </div>
    
            <div class="row mb-4">
                @foreach ([['label' => 'Tổng số bài tập', 'count' => $assignments ? count($assignments) : 0, 'icon' => 'calendar', 'color' => 'primary'], ['label' => 'Số học sinh tham gia', 'count' => $students ? count($students) : 0, 'icon' => 'user', 'color' => 'success'], ['label' => 'Tổng số câu hỏi', 'count' => $questions ? count($questions) : 0, 'icon' => 'clipboard-list', 'color' => 'info'], ['label' => 'Tổng số câu trả lời', 'count' => $choices ? count($choices) : 0, 'icon' => 'comments', 'color' => 'warning']] as $card)
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
        </div>
    </div>
@endsection
