@extends('layouts.app')
@section('title', __('app.gradebook') . ' - ' . $course->title)
@section('page-title', __('app.gradebook') . ': ' . $course->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->title }}</a> / <span>{{ __('app.gradebook') }}</span>
</div>

<div class="card mb-6">
    <div class="card-header flex justify-between items-center">
        <span class="card-title">Rekap Nilai Siswa</span>
        <a href="{{ route('teacher.courses.gradebook.export', $course) }}" class="btn btn-sm btn-success">Ekspor ke Excel</a>
    </div>
    <div class="card-body">
        <p class="text-muted text-sm mb-4">Tabel di bawah ini menampilkan rekapitulasi nilai tugas dan kuis untuk seluruh siswa yang terdaftar di kelas <strong>{{ $course->title }}</strong>.</p>
        
        <div class="table-wrap" style="overflow-x: auto;">
            <table class="table-striped" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th style="min-width: 150px; position: sticky; left: 0; background: #fff; z-index: 1;">Nama Siswa</th>
                        @foreach($assignments as $assignment)
                            <th title="{{ $assignment->title }}" class="text-center" style="min-width: 100px;">
                                Tugas:<br>
                                <span class="font-normal text-xs text-muted">{{ Str::limit($assignment->title, 15) }}</span>
                            </th>
                        @endforeach
                        @foreach($quizzes as $quiz)
                            <th title="{{ $quiz->title }}" class="text-center" style="min-width: 100px;">
                                Kuis:<br>
                                <span class="font-normal text-xs text-muted">{{ Str::limit($quiz->title, 15) }}</span>
                            </th>
                        @endforeach
                        <th class="text-center" style="min-width: 80px;">Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradebook as $data)
                        <tr>
                            <td class="font-semibold" style="position: sticky; left: 0; background: #fff; z-index: 1; border-right: 1px solid #eee;">
                                {{ $data['student']->name }}
                            </td>
                            
                            {{-- Assignments --}}
                            @foreach($assignments as $assignment)
                                <td class="text-center">
                                    @php $score = $data['assignments'][$assignment->id]; @endphp
                                    @if($score !== null)
                                        <span class="{{ $score < 70 ? 'text-danger' : 'text-success' }} font-semibold">{{ $score }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                            
                            {{-- Quizzes --}}
                            @foreach($quizzes as $quiz)
                                <td class="text-center">
                                    @php $score = $data['quizzes'][$quiz->id]; @endphp
                                    @if($score !== null)
                                        <span class="{{ $score < 70 ? 'text-danger' : 'text-success' }} font-semibold">{{ number_format($score, 1) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                            
                            {{-- Average --}}
                            <td class="text-center font-bold bg-gray-50">
                                {{ number_format($data['average_score'], 1) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + count($assignments) + count($quizzes) }}" class="text-center p-4 text-muted">Belum ada siswa yang terdaftar di kelas ini atau belum ada tugas/kuis yang dinilai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
