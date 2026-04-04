<table>
    <thead>
        <tr>
            <th colspan="{{ 3 + count($assignments) + count($quizzes) }}">
                <strong>Rekap Nilai Kelas: {{ $course->title }}</strong>
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            @foreach($assignments as $assignment)
                <th>Tugas: {{ $assignment->title }}</th>
            @endforeach
            @foreach($quizzes as $quiz)
                <th>Kuis: {{ $quiz->title }}</th>
            @endforeach
            <th>Rata-rata Keseluruhan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($gradebook as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data['student']->name }}</td>
                @foreach($assignments as $assignment)
                    @php $score = $data['assignments'][$assignment->id]; @endphp
                    <td>{{ $score !== null ? $score : '-' }}</td>
                @endforeach
                @foreach($quizzes as $quiz)
                    @php $score = $data['quizzes'][$quiz->id]; @endphp
                    <td>{{ $score !== null ? number_format($score, 2) : '-' }}</td>
                @endforeach
                <td>{{ number_format($data['average_score'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
