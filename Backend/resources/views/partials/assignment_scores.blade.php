<div class="mt-3">
    <h3>Điểm của học sinh</h3>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Tên học sinh</th>
                    <th class="text-center">Thời gian làm</th>
                    <th class="text-center">Số câu trả lời đúng</th>
                    <th class="text-center">Điểm</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assignment->submits as $submit)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $submit->student->name }}</td>
                        <td class="text-center">{{$submit->created_at}}</td>
                        <td class="text-center">10/10</td>
                        <td class="text-center">{{ $submit->score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
