@extends('layouts.app')
@section('title', 'Kuis: ' . $quiz->title)
@section('page-title', $quiz->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('student.courses.show', $quiz->course_id) }}">{{ __('Kembali ke Kelas') }}</a> / <span>{{ $quiz->title }}</span>
</div>

@if($attempt)
<div class="card mb-6" style="border-left: 4px solid #3b82f6;">
    <div class="card-body">
        <h3 class="font-semibold text-lg text-blue-600 mb-2">Anda sudah mengerjakan kuis ini</h3>
        <p>Waktu Pengerjaan: {{ $attempt->start_time->format('d M Y H:i') }}</p>
        <div class="mt-4 text-2xl font-bold">
            Nilai Anda: <span class="{{ $attempt->score !== null ? 'text-green-600' : 'text-yellow-600' }}">{{ $attempt->score !== null ? $attempt->score : 'Status: Menunggu Penilaian Guru (Ada Essay)' }}</span>
        </div>
        <div class="mt-4">
            <a href="{{ route('student.courses.show', $quiz->course_id) }}" class="btn btn-primary">Kembali ke Beranda Kelas</a>
        </div>
    </div>
</div>
@else
<div class="grid grid-2 mb-6" style="align-items: start;">
    <div class="card" style="position: sticky; top: 20px;">
        <div class="card-header"><span class="card-title">{{ __('Informasi Kuis') }}</span></div>
        <div class="card-body">
            <p>{{ $quiz->description ?: 'Kerjakan soal-soal berikut dengan teliti.' }}</p>
            <hr class="my-4">
            <div class="flex justify-between font-semibold">
                <span>Soal: {{ $quiz->questions->count() }} Poin</span>
                <span>Durasi: {{ $quiz->duration ? $quiz->duration . ' Menit' : 'Bebas' }}</span>
            </div>
            
            @if($quiz->duration)
            <div class="mt-4 text-center p-3 bg-red-50 text-red-600 rounded-lg border border-red-200" id="timer-container">
                <span class="text-sm block mb-1">Sisa Waktu:</span>
                <span class="text-3xl font-bold" id="timer">--:--</span>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Lembar Jawaban</span></div>
        
        @if($quiz->questions->count() === 0)
            <div class="card-body">
                <div class="empty-state p-4 text-center">Belum ada soal untuk kuis ini.</div>
            </div>
        @else
            <!-- GERBANG KUIS -->
            <div class="card-body text-center py-10" id="gate-container">
                <div class="mb-6 inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 text-blue-500">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Siap Mengerjakan Kuis?</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Pastikan koneksi internet Anda stabil. Setelah Anda menekan tombol mulai, waktu akan terus berjalan dan tidak dapat dihentikan bahkan jika Anda memuat ulang halaman.</p>
                <button type="button" id="btn-start-quiz" class="btn btn-primary px-8 py-3 text-lg font-semibold shadow-lg hover:shadow-xl transition-all">Mulai Kerjakan Kuis</button>
            </div>

            <!-- SOAL KUIS -->
            <div class="card-body" id="quiz-container" style="display: none;">
                <form method="POST" action="{{ route('student.quizzes.submit', $quiz) }}" id="quiz-form">
                    @csrf
                    @foreach($quiz->questions as $index => $q)
                    <div class="mb-6 pb-6 
                        {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <h4 class="font-semibold mb-3">{{ $index + 1 }}. {{ $q->question_text }} <span class="text-sm text-blue-500 font-normal ml-2">({{ $q->points }} Poin)</span></h4>
                        
                        @if($q->type === 'multiple_choice')
                            <div class="space-y-2 ml-4">
                                @foreach($q->options as $opt)
                                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 rounded">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}" required>
                                    <span>{{ $opt->option_text }}</span>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <textarea name="answers[{{ $q->id }}]" rows="4" class="w-full border rounded p-3" placeholder="Ketik jawaban essay Anda di sini..." required></textarea>
                        @endif
                    </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary w-full text-lg mt-4">Kumpulkan Jawaban</button>
                </form>
            </div>
        @endif
    </div>
</div>

@if(!$attempt && $quiz->questions->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let gateContainer = document.getElementById('gate-container');
        let quizContainer = document.getElementById('quiz-container');
        let startBtn = document.getElementById('btn-start-quiz');
        let formElement = document.getElementById('quiz-form');
        
        @if($quiz->duration)
        let durationMinutes = {{ $quiz->duration }};
        let durationSeconds = durationMinutes * 60;
        let timerElement = document.getElementById('timer');
        let interval;
        @endif

        startBtn.addEventListener('click', function() {
            // Sembunyikan gerbang, tampilkan kuis
            gateContainer.style.display = 'none';
            quizContainer.style.display = 'block';
            
            @if($quiz->duration)
            // Inisialisasi tampilan timer
            let initialMinutes = Math.floor(durationSeconds / 60);
            let initialSeconds = durationSeconds % 60;
            initialMinutes = initialMinutes < 10 ? '0' + initialMinutes : initialMinutes;
            initialSeconds = initialSeconds < 10 ? '0' + initialSeconds : initialSeconds;
            timerElement.textContent = initialMinutes + ':' + initialSeconds;

            // Mulai countdown
            interval = setInterval(function() {
                durationSeconds--;
                
                let minutes = Math.floor(durationSeconds / 60);
                let seconds = durationSeconds % 60;

                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerElement.textContent = minutes + ':' + seconds;

                if (durationSeconds <= 0) {
                    clearInterval(interval);
                    alert('Waktu habis! Jawaban Anda akan dikumpulkan secara otomatis.');
                    // Bypass validation for auto-submit
                    let inputs = formElement.querySelectorAll('[required]');
                    inputs.forEach(i => i.removeAttribute('required'));
                    formElement.submit();
                }
            }, 1000);
            @endif
        });
    });
</script>
@endif

@endif
@endsection
