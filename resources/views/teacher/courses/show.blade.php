@extends('layouts.app')
@section('title', $course->title)
@section('page-title', $course->title)

@section('content')
<style>
    .course-tabs { display: flex; gap: 8px; flex-wrap: wrap; border-bottom: 1px solid var(--border); margin-bottom: 24px; padding-bottom: 12px; }
    .tab-btn { padding: 6px 14px; background: #fff; border: 1px solid var(--border); border-radius: 20px; font-weight: 500; font-size: 0.85rem; color: var(--text-muted); cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .tab-btn:hover { color: var(--text); background: var(--bg); }
    .tab-btn.active { color: #fff; background: var(--primary); border-color: var(--primary); }
    .tab-content { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="breadcrumb">
    <a href="{{ route('teacher.courses.index') }}">{{ __('app.courses') }}</a> / <span>{{ $course->title }}</span>
</div>

<div x-data="{ activeTab: 'overview' }">
    <div class="course-tabs">
        <button class="tab-btn" :class="{ 'active': activeTab === 'overview' }" @click="activeTab = 'overview'">Ringkasan</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'materials' }" @click="activeTab = 'materials'">Materi ({{ $course->materials->count() }})</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'assignments' }" @click="activeTab = 'assignments'">Tugas ({{ $course->assignments->count() }})</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'quizzes' }" @click="activeTab = 'quizzes'">Kuis / Ujian</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'attendances' }" @click="activeTab = 'attendances'">Presensi ({{ $course->attendances->count() }})</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'students' }" @click="activeTab = 'students'">Siswa Terdaftar ({{ $studentCount }})</button>
    </div>

    <!-- TAB: OVERVIEW -->
    <div class="tab-content" x-show="activeTab === 'overview'" x-cloak>
        <div class="grid grid-2">
            <div class="card">
                <div class="card-header"><span class="card-title">{{ __('app.course') }} {{ __('app.details') ?? 'Details' }}</span></div>
                <div class="card-body">
                    <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 10px;">{{ $course->title }}</h3>
                    <p style="color: var(--text-muted); line-height: 1.6;">{{ $course->description ?? 'Tidak ada deskripsi untuk kelas ini.' }}</p>
                    
                    <div style="margin-top: 20px; display: flex; gap: 10px;">
                        <span class="badge badge-primary">{{ $studentCount }} {{ __('app.students') ?? 'Students' }}</span>
                        <span class="badge badge-secondary">{{ $course->materials->count() }} {{ __('app.materials') }}</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><span class="card-title">{{ __('app.quick_actions') ?? 'Aksi Cepat' }}</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                    <a href="{{ route('teacher.materials.create', ['course_id' => $course->id]) }}" class="btn btn-outline">{{ __('app.upload') }} {{ __('app.materials') }}</a>
                    <a href="{{ route('teacher.assignments.create', ['course_id' => $course->id]) }}" class="btn btn-outline">{{ __('app.create_assignment') }}</a>
                    <a href="{{ route('teacher.attendance.create', ['course_id' => $course->id]) }}" class="btn btn-outline">{{ __('app.create_attendance') }}</a>
                    <a href="{{ route('teacher.grades.show', $course) }}" class="btn btn-primary" style="text-align:center;">Sistem Penilaian (Rekap & Persentase)</a>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB: MATERIALS -->
    <div class="tab-content" x-show="activeTab === 'materials'" x-cloak>
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ __('app.materials') }}</span>
                <a href="{{ route('teacher.materials.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-primary">{{ __('app.upload') }} {{ __('app.materials') }}</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul Materi</th><th>Tipe</th><th>Tanggal</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->materials as $mat)
                        <tr>
                            <td class="font-semibold">{{ $mat->title }}</td>
                            <td><span class="badge badge-secondary">{{ strtoupper($mat->type) }}</span></td>
                            <td>{{ $mat->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex gap-2">
                                <a href="{{ route('teacher.materials.show', $mat) }}" class="btn btn-sm btn-outline">{{ __('app.view_details') }}</a>
                                <a href="{{ route('teacher.materials.edit', $mat) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                <form method="POST" action="{{ route('teacher.materials.destroy', $mat) }}" onsubmit="return confirm('{{ __('app.delete_material_confirm') }}')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding: 20px;">{{ __('app.no_materials_yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: ASSIGNMENTS -->
    <div class="tab-content" x-show="activeTab === 'assignments'" x-cloak>
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ __('app.assignments') }}</span>
                <a href="{{ route('teacher.assignments.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-primary">{{ __('app.create_assignment') }}</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul Tugas</th><th>Batas Waktu</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->assignments as $asgn)
                        <tr>
                            <td class="font-semibold">{{ $asgn->title }}</td>
                            <td>{{ $asgn->deadline->format('d M Y H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                <a href="{{ route('teacher.assignments.show', $asgn) }}" class="btn btn-sm btn-outline">{{ __('app.view_details') }}</a>
                                <a href="{{ route('teacher.assignments.edit', $asgn) }}" class="btn btn-sm btn-warning">{{ __('app.edit') ?? 'Edit' }}</a>
                                <form method="POST" action="{{ route('teacher.assignments.destroy', $asgn) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') ?? 'Hapus' }}</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted" style="padding:20px;">{{ __('app.no_assignments_yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: QUIZZES -->
    <div class="tab-content" x-show="activeTab === 'quizzes'" x-cloak>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Kuis / UTS / UAS</span>
                <a href="{{ route('teacher.quizzes.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-primary">Buat Kuis / Ujian Baru</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul Kuis</th><th>{{ __('Durasi') }}</th><th>Total Soal</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->quizzes as $quiz)
                        <tr>
                            <td class="font-semibold">{{ $quiz->title }}</td>
                            <td>{{ $quiz->duration ? $quiz->duration . ' Menit' : '-' }}</td>
                            <td>{{ $quiz->questions->count() }}</td>
                            <td>
                                <div class="flex gap-2">
                                <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-sm btn-outline">{{ __('app.view_details') }}</a>
                                <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                <form method="POST" action="{{ route('teacher.quizzes.destroy', $quiz) }}" onsubmit="return confirm('Hapus kuis ini?')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada kuis untuk kelas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: ATTENDANCES -->
    <div class="tab-content" x-show="activeTab === 'attendances'" x-cloak>
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ __('app.attendance') ?? 'Presensi' }}</span>
                <a href="{{ route('teacher.attendance.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-primary">{{ __('app.create_attendance') }}</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul Sesi</th><th>Tanggal</th><th>Jam</th><th>{{ __('Aksi') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->attendances as $att)
                        <tr>
                            <td class="font-semibold">{{ $att->title }}</td>
                            <td>{{ $att->date->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($att->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($att->end_time)->format('H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                <a href="{{ route('teacher.attendance.show', $att) }}" class="btn btn-sm btn-outline">{{ __('app.view_details') }}</a>
                                <a href="{{ route('teacher.attendance.edit', $att) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                <form method="POST" action="{{ route('teacher.attendance.destroy', $att) }}" onsubmit="return confirm('Hapus sesi absensi ini?')" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">{{ __('app.no_active_sessions') ?? 'Belum ada absensi untuk kelas ini.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB: STUDENTS (AJAX PAGINATION) -->
    <div class="tab-content" x-show="activeTab === 'students'" x-cloak
         x-data="{ 
             htmlContent: '<div style=\'padding:30px; text-align:center; color:#64748b;\'>Memuat data siswa...</div>',
             loadStudents(url = '{{ route('teacher.courses.students', $course) }}') {
                 fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                 .then(res => res.text())
                 .then(html => this.htmlContent = html);
             }
         }"
         x-init="$watch('activeTab', val => { if(val === 'students' && htmlContent.includes('Memuat data')) loadStudents() })"
         @click.prevent="if($event.target.closest('.pagination') && $event.target.tagName==='A') { loadStudents($event.target.href) }"
    >
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ __('app.enrolled') }} {{ __('app.student') }} ({{ $studentCount }})</span>
            </div>
            <div x-html="htmlContent"></div>
        </div>
    </div>
</div>
@endsection
