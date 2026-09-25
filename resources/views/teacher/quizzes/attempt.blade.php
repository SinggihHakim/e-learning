@extends('layouts.app')
@section('title', $quiz->title . ' - ' . __('app.grade_quiz'))
@section('page-title', __('app.grade_quiz') . ': ' . $quiz->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('teacher.courses.show', $quiz->course_id) }}">{{ $quiz->course->title }}</a> /
    <a href="{{ route('teacher.quizzes.show', $quiz) }}">{{ $quiz->title }}</a> /
    <span>{{ $attempt->student->name }}</span>
</div>

<div class="card mb-4">
    <div class="card-body" style="display:flex;gap:24px;flex-wrap:wrap;">
        <div><span class="text-muted text-sm">{{ __('app.student') }}</span><p class="font-semibold">{{ $attempt->student->name }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.started_at') }}</span><p class="font-semibold">{{ $attempt->start_time->format('d M Y H:i') }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.completed_at') }}</span><p class="font-semibold">{{ $attempt->end_time ? $attempt->end_time->format('d M Y H:i') : '-' }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.current_score') }}</span><p class="font-semibold">{{ number_format($attempt->score, 2) }} / 100</p></div>
    </div>
</div>

<form action="{{ route('teacher.quizzes.attempts.grade', [$quiz->id, $attempt->id]) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="card-header"><span class="card-title">{{ __('app.student_answers') }}</span></div>
        <div class="card-body">
            @foreach($attempt->answers as $index => $answer)
                <div class="quiz-question mb-4 p-4 border rounded">
                    <div class="font-semibold mb-2">{{ $index + 1 }}. {!! nl2br(e($answer->question->question_text)) !!}</div>
                    
                    @if($answer->question->type === 'multiple_choice')
                        <div class="mb-2">
                            <span class="text-muted">Jawaban Siswa:</span>
                            @if($answer->quiz_question_option_id)
                                @php
                                    $selectedOption = $answer->question->options->where('id', $answer->quiz_question_option_id)->first();
                                @endphp
                                <p class="{{ $answer->is_correct ? 'text-success font-semibold' : 'text-danger font-semibold' }}">
                                    {{ $selectedOption ? $selectedOption->option_text : '-' }} 
                                    @if($answer->is_correct)
                                        (Benar, {{ $answer->question->points }} Poin)
                                    @else
                                        (Salah, 0 Poin)
                                    @endif
                                </p>
                            @else
                                <p class="text-muted">Tidak dijawab (0 Poin)</p>
                            @endif
                        </div>
                    @elseif($answer->question->type === 'essay')
                        <div class="mb-3">
                            <span class="text-muted">Jawaban Siswa:</span>
                            <div class="p-3 bg-light rounded" style="background: #f8f9fa; border: 1px solid #dee2e6;">
                                {!! nl2br(e($answer->essay_answer ?: 'Tidak ada jawaban')) !!}
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label class="col-sm-2 col-form-label font-semibold text-primary">Nilai / Poin (Max: {{ $answer->question->points }})</label>
                            <div class="col-sm-2">
                                <input type="number" name="points[{{ $answer->id }}]" class="form-control" 
                                    value="{{ old('points.'.$answer->id, $answer->points_awarded) }}" 
                                    min="0" max="{{ $answer->question->points }}" required>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="card-action">
            <button type="submit" class="btn btn-primary">{{ __('app.save_grade') }}</button>
            <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline">{{ __('app.cancel') }}</a>
        </div>
    </div>
</form>
@endsection
