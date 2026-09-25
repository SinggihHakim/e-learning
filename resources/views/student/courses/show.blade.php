@extends('layouts.app')
@section('title', $course->title)
@section('page-title', $course->title)

@section('content')
<style>
    .course-tabs { display: flex; gap: 8px; flex-wrap: wrap; border-bottom: 1px solid var(--border); margin-bottom: 24px; padding-bottom: 12px; position: relative; z-index: 20; }
    .tab-btn { padding: 6px 14px; background: #fff; border: 1px solid var(--border); border-radius: 20px; font-weight: 500; font-size: 0.85rem; color: var(--text-muted); cursor: pointer; transition: all 0.2s; white-space: nowrap; position: relative; z-index: 25; pointer-events: auto; }
    .tab-btn:hover { color: var(--text); background: var(--bg); transform: translateY(-1px); }
    .tab-btn.active { color: #fff; background: var(--primary); border-color: var(--primary); box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); }
    .tab-content { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="breadcrumb">
    <a href="{{ route('student.courses.index') }}">{{ __('app.courses') }}</a> / <span>{{ $course->title }}</span>
</div>

<div x-data="{ activeTab: 'overview' }">
    <div class="course-tabs">
        <button class="tab-btn" :class="{ 'active': activeTab === 'overview' }" @click="activeTab = 'overview'">Ringkasan</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'materials' }" @click="activeTab = 'materials'">Materi ({{ $course->materials->count() }})</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'assignments' }" @click="activeTab = 'assignments'">Tugas ({{ $course->assignments->count() }})</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'quizzes' }" @click="activeTab = 'quizzes'">Kuis / Ujian</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'attendances' }" @click="activeTab = 'attendances'">Presensi</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'grades' }" @click="activeTab = 'grades'">{{ __('Nilai') }}</button>
    </div>

    <!-- TAB: OVERVIEW -->
    <div class="tab-content" x-show="activeTab === 'overview'" x-cloak>
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="text-xl font-semibold mb-2">Tentang Kelas</h2>
                <p class="text-muted" style="line-height: 1.6;">{{ $course->description ?: 'Tidak ada deskripsi untuk kelas ini.' }}</p>
                <div class="mt-4 flex flex-wrap" style="gap: 16px; font-size: 0.9rem; color: var(--text-muted);">
                    <span>Pengajar: <strong style="color: var(--text);">{{ $course->teacher->name }}</strong></span>
                    <span>Pelajar Terdaftar: <strong style="color: var(--text);">{{ $course->students->count() }} Siswa</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB: MATERIALS -->
    <div class="tab-content" x-show="activeTab === 'materials'" x-cloak>
        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.learning_materials') }}</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul Materi</th><th>Tipe</th><th>Tanggal</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->materials as $mat)
                        @php $isCompleted = in_array($mat->id, $completedMaterials); @endphp
                        <tr style="{{ $isCompleted ? 'background-color: #f0fdf4;' : '' }}">
                            <td class="font-semibold">
                                <a href="{{ route('student.materials.show', $mat) }}" style="color:var(--text);text-decoration:none;">{{ $mat->title }}</a>
                                @if($isCompleted) <div style="color: #047857; font-size:0.75rem; margin-top:4px;">&check; Selesai</div> @endif
                            </td>
                            <td><span class="badge badge-secondary">{{ strtoupper($mat->type) }}</span></td>
                            <td>{{ $mat->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex gap-2">
                                @if(!$isCompleted)
                                <form method="POST" action="{{ route('student.materials.complete', $mat) }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline">{{ __('app.mark_done') }}</button>
                                </form>
                                @endif
                                @if($mat->file_path)
                                <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" class="btn btn-sm btn-primary">{{ __('app.download') }}</a>
                                @elseif($mat->video_link)
                                <a href="{{ $mat->video_link }}" target="_blank" class="btn btn-sm btn-primary">{{ __('app.watch') }}</a>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">{{ __('app.no_materials_yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: ASSIGNMENTS -->
    <div class="tab-content" x-show="activeTab === 'assignments'" x-cloak>
        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.assignments') }}</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul Tugas</th><th>Batas Waktu</th><th>Status</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->assignments as $asgn)
                        @php $sub = $submissions->get($asgn->id); @endphp
                        <tr>
                            <td class="font-semibold">{{ $asgn->title }}</td>
                            <td>{{ $asgn->deadline->format('d M Y H:i') }}</td>
                            <td>
                                @if($sub)
                                <span class="badge {{ $sub->score !== null ? 'badge-success' : 'badge-warning' }}">
                                    {{ $sub->score !== null ? __('app.graded').': '.$sub->score : __('app.submitted') }}
                                </span>
                                @else
                                    @if($asgn->isExpired())
                                    <span class="badge badge-danger">{{ __('app.missed') }}</span>
                                    @else
                                    <span class="badge badge-secondary">Belum Dikerjakan</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if(!$sub && !$asgn->isExpired())
                                <a href="{{ route('student.assignments.show', $asgn) }}" class="btn btn-sm btn-outline">{{ __('app.start_task') }}</a>
                                @elseif($sub)
                                <a href="{{ route('student.assignments.show', $asgn) }}" class="btn btn-sm btn-outline">Lihat Kumpulan</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">{{ __('app.no_assignments_yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: QUIZZES -->
    <div class="tab-content" x-show="activeTab === 'quizzes'" x-cloak>
        <div class="card">
            <div class="card-header"><span class="card-title">Quizzes</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul Kuis</th><th>Waktu</th><th>Status</th><th>Skor</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->quizzes as $quiz)
                        @php $attempt = $quizAttempts->get($quiz->id); @endphp
                        <tr>
                            <td class="font-semibold">{{ $quiz->duration ? $quiz->title : $quiz->title }}</td>
                            <td>{{ $quiz->duration ? $quiz->duration . ' Menit' : 'Bebas' }}</td>
                            <td>
                                @if($attempt)
                                <span class="badge badge-success">{{ __('Selesai') }}</span>
                                @else
                                <span class="badge badge-warning">Belum Dikerjakan</span>
                                @endif
                            </td>
                            <td class="font-bold">{{ $attempt ? $attempt->score : '-' }}</td>
                            <td>
                                @if(!$attempt)
                                <a href="{{ route('student.quizzes.show', $quiz) }}" class="btn btn-sm btn-outline">Mulai Kuis</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted" style="padding:20px;">Belum ada kuis tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: ATTENDANCES -->
    <div class="tab-content" x-show="activeTab === 'attendances'" x-cloak>
        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.attendance') ?? 'Presensi' }}</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Jadwal Presensi</th><th>Tanggal & Waktu</th><th>Status</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->attendances as $att)
                        @php $rec = $attendancesRecords->get($att->id); @endphp
                        <tr>
                            <td class="font-semibold">{{ $att->title }}</td>
                            <td>{{ $att->date->format('d M Y') }} ({{ \Carbon\Carbon::parse($att->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($att->end_time)->format('H:i') }})</td>
                            <td>
                                @if($rec)
                                    @php
                                        $statusColors = ['hadir' => 'badge-success', 'telat' => 'badge-warning', 'izin' => 'badge-purple', 'tidak_hadir' => 'badge-danger'];
                                        $statusLabels = ['hadir' => __('app.present'), 'telat' => __('app.late'), 'izin' => __('app.excused'), 'tidak_hadir' => __('app.absent')];
                                    @endphp
                                    <span class="badge {{ $statusColors[$rec->status] ?? 'badge-secondary' }}">{{ $statusLabels[$rec->status] ?? $rec->status }}</span>
                                @else
                                    <span class="badge badge-warning">Belum Presensi</span>
                                @endif
                            </td>
                            <td>
                                @if(!$rec)
                                    <form method="POST" action="{{ route('student.attendance.submit', $att) }}" style="display:inline-flex;gap:8px;">
                                        @csrf
                                        @if($att->password)
                                        <input type="text" name="password" placeholder="{{ __('app.password') }}" required style="max-width:100px;padding:6px;border:1px solid #ccc;border-radius:4px;">
                                        @else
                                        <input type="hidden" name="password" value="">
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-sm">{{ __('app.submit_present') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">{{ __('app.no_history') ?? 'Belum ada jadwal absensi.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: GRADES -->
    <div class="tab-content" x-show="activeTab === 'grades'" x-cloak>
        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.grades') ?? 'Course Grades' }}</span></div>
            <div class="card-body">
                <div class="empty-state" style="padding:20px;"><p>Tinjauan nilai akan ditampilkan di sini.</p></div>
            </div>
        </div>
    </div>
</div>
@endsection
