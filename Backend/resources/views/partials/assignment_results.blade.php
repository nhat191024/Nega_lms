<div class="mt-3">
    <h3>Kết quả bài tập lab</h3>
    <ul class="list-group">
        @foreach ($assignment->submits as $submit)
            <li class="list-group-item">
                <h5>Học sinh: {{ $submit->student->name }}</h5>
                <p>Kết quả: {{ $submit->answer }}</p>
            </li>
        @endforeach
    </ul>
</div>
