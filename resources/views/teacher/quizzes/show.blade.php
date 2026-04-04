@extends('layouts.app')
@section('title', $quiz->title)
@section('page-title', 'Detail Kuis')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('teacher.courses.show', $quiz->course_id) }}">{{ __('Kembali ke Kelas') }}</a> / <span>{{ $quiz->title }}</span>
</div>

<div class="grid grid-2 mb-6">
    <div class="card">
        <div class="card-header"><span class="card-title">{{ __('Informasi Kuis') }}</span></div>
        <div class="card-body">
            <h2 class="text-xl font-semibold">{{ $quiz->title }}</h2>
            <p class="text-muted mt-2">{{ $quiz->description ?: 'Tidak ada deskripsi' }}</p>
            <div class="mt-4 flex gap-4 text-sm">
                <span>Durasi: <strong>{{ $quiz->duration ? $quiz->duration . ' Menit' : 'Tidak Dibatasi' }}</strong></span>
                <span>Jumlah Soal: <strong>{{ $quiz->questions->count() }}</strong></span>
            </div>
            <div class="mt-4">
                <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-sm btn-warning">{{ __('Edit Kuis') }}</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><span class="card-title">{{ __('Tambah Soal Baru') }}</span></div>
        <div class="card-body" x-data="{ type: 'multiple_choice' }">
            <form method="POST" action="{{ route('teacher.quizzes.questions.store', $quiz) }}">
                @csrf
                <div class="form-group">
                    <label>{{ __('Tipe Soal') }}</label>
                    <select name="type" x-model="type">
                        <option value="multiple_choice">{{ __('Pilihan Ganda') }}</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>{{ __('Pertanyaan') }}</label>
                    <textarea name="question_text" rows="2" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>{{ __('Poin/Bobot Nilai') }}</label>
                    <input type="number" name="points" value="10" min="1" required>
                </div>

                <div x-show="type === 'multiple_choice'" class="mt-4 pb-4 border-t pt-4">
                    <label class="font-semibold block mb-2">{{ __('Jawaban (Centang yang benar)') }}</label>
                    <div class="space-y-2">
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="correct_option" value="0" required x-bind:required="type === 'multiple_choice'">
                            <input type="text" name="options[0][text]" placeholder="{{ __('Opsi A') }}" x-bind:required="type === 'multiple_choice'" class="w-full">
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="correct_option" value="1">
                            <input type="text" name="options[1][text]" placeholder="{{ __('Opsi B') }}" x-bind:required="type === 'multiple_choice'" class="w-full">
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="correct_option" value="2">
                            <input type="text" name="options[2][text]" placeholder="{{ __('Opsi C') }}" class="w-full">
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="correct_option" value="3">
                            <input type="text" name="options[3][text]" placeholder="{{ __('Opsi D') }}" class="w-full">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">{{ __('Simpan Soal') }}</button>
            </form>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">{{ __('Daftar Soal') }}</span></div>
    <div class="card-body">
        @forelse($quiz->questions as $index => $q)
        <div class="mb-4 pb-4 border-b">
            <h4 class="font-semibold">{{ $index + 1 }}. {{ $q->question_text }}</h4>
            <div class="text-sm text-muted mb-2">Tipe: {{ $q->type == 'essay' ? 'Essay' : 'Pilihan Ganda' }} | Bobot: {{ $q->points }} Poin</div>
            
            @if($q->type == 'multiple_choice')
            <ul class="ml-4 list-disc text-sm">
                @foreach($q->options as $opt)
                <li class="{{ $opt->is_correct ? 'text-green-600 font-bold' : '' }}">{{ $opt->option_text }} {!! $opt->is_correct ? '&#10003;' : '' !!}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @empty
        <div class="empty-state text-center p-4">{{ __('Belum ada soal dibuat.') }}</div>
        @endforelse
    </div>
</div>
</div>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">{{ __('Daftar Pengerjaan Siswa (Attempts)') }}</span></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Siswa') }}</th>
                    <th>{{ __('Waktu Mulai') }}</th>
                    <th>{{ __('Waktu Selesai') }}</th>
                    <th>{{ __('Nilai') }}</th>
                    <th>{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quiz->attempts as $attempt)
                <tr>
                    <td>{{ $attempt->student->name }}</td>
                    <td>{{ $attempt->start_time->format('d M Y H:i') }}</td>
                    <td>{{ $attempt->end_time ? $attempt->end_time->format('d M Y H:i') : 'Sedang mengerjakan' }}</td>
                    <td>
                        @if($attempt->end_time)
                            <span class="badge {{ $attempt->score !== null ? 'badge-success' : 'badge-warning' }}">
                                {{ $attempt->score !== null ? number_format($attempt->score, 2) . '/100' : 'Belum Dinilai' }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($attempt->end_time)
                        <a href="{{ route('teacher.quizzes.attempts.show', [$quiz->id, $attempt->id]) }}" class="btn btn-sm btn-primary">{{ __('Lihat & Beri Nilai') }}</a>
                        @else
                        <span class="text-muted text-sm">{{ __('Menunggu selesai') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center p-4">{{ __('Belum ada siswa yang mengerjakan.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
