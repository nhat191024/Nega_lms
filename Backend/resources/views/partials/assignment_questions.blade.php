<div class="mt-3">
    <h3>Danh sách câu hỏi</h3>
    <ul class="list-group">
        @foreach ($assignment->quizzes as $quiz)
            <li class="list-group-item">
                <h5>{{ $quiz->question }}</h5>
                <ul>
                    @foreach ($quiz->choices as $choice)
                        <li>{{ $choice->choice }} @if($choice->is_correct) <span class="badge bg-success">Đúng</span> @endif</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
