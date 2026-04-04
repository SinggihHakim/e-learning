@extends('layouts.app')
@section('title', __('app.grades') . ' - ' . $course->title)
@section('page-title', __('app.grades') . ': ' . $course->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('teacher.grades.index') }}">{{ __('app.grades') }}</a> / <span>{{ $course->title }}</span>
</div>

<div x-data="{ activeTab: 'setup' }">
    <div class="course-tabs" style="display:flex; gap:16px; border-bottom:1px solid var(--border); margin-bottom:24px;">
        <button class="tab-btn" :class="{ 'active': activeTab === 'setup' }" @click="activeTab = 'setup'" style="padding:10px; background:transparent; border:none; border-bottom: 2px solid transparent; font-weight:600; cursor:pointer;" :style="activeTab === 'setup' ? 'border-bottom-color: var(--primary); color: var(--primary)' : 'color: var(--text)'">
            1. Persentase & Nilai Akhir
        </button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'gradebook' }" @click="activeTab = 'gradebook'" style="padding:10px; background:transparent; border:none; border-bottom: 2px solid transparent; font-weight:600; cursor:pointer;" :style="activeTab === 'gradebook' ? 'border-bottom-color: var(--primary); color: var(--primary)' : 'color: var(--text)'">
            2. Rincian Skor Lengkap (Tugas & Kuis)
        </button>
    </div>

    <!-- TAB 1: PERSENTASE -->
    <div x-show="activeTab === 'setup'" x-cloak>
        <div class="grid grid-2 mb-6">
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('app.grade_components') }}</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.grades.components.store', $course) }}" class="flex gap-2 mb-4" style="flex-wrap:wrap;">
                @csrf
                <input type="text" name="name" placeholder="{{ __('app.component_name') }}" required style="flex:1;min-width:140px;">
                <input type="number" name="weight" placeholder="{{ __('app.weight') }} %" min="1" max="100" required style="width:100px;">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('app.add') }}</button>
            </form>

            @if($components->isEmpty())
            <p class="text-muted text-sm">{{ __('app.no_grade_components') }}</p>
            @else
            @php $totalWeight = $components->sum('weight'); @endphp
            <table>
                <thead><tr><th>{{ __('app.component') }}</th><th>{{ __('app.weight') }}</th><th></th></tr></thead>
                <tbody>
                    @foreach($components as $comp)
                    <tr>
                        <td class="font-semibold">{{ $comp->name }}</td>
                        <td><span class="badge badge-primary">{{ $comp->weight }}%</span></td>
                        <td>
                            <form method="POST" action="{{ route('teacher.grades.components.destroy', $comp) }}" onsubmit="return confirm('{{ __('app.remove_student_confirm') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('app.remove') }}</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="font-semibold">{{ __('app.total') }}</td>
                        <td><span class="badge {{ $totalWeight == 100 ? 'badge-success' : 'badge-warning' }}">{{ $totalWeight }}%</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('app.quick_actions') }}</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('teacher.grades.export', $course) }}" class="btn btn-success">{{ __('app.export_excel') }}</a>
            <a href="{{ route('teacher.leaderboard', $course) }}" class="btn btn-outline">{{ __('app.view_leaderboard') }}</a>
        </div>
    </div>
</div>

@if($students->isNotEmpty() && $components->isNotEmpty())
<div class="card">
    <div class="card-header">
        <span class="card-title">{{ __('app.grade_input') }}</span>
        <span class="text-muted text-sm">{{ __('app.grade_input_hint') }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>{{ __('app.student') }}</th>
                    @foreach($components as $comp)
                    <th>{{ $comp->name }}<br><small style="font-weight:400;text-transform:none;">{{ $comp->weight }}%</small><br>
                        @if($mappedComponents[$comp->id])
                        <span class="badge badge-secondary" style="font-size:0.6rem;">Otomatis</span>
                        @endif
                    </th>
                    @endforeach
                    <th>Total (100%)</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="font-semibold">{{ $student->name }}</td>
                    @foreach($components as $comp)
                    <td>
                        @if($mappedComponents[$comp->id])
                        <span class="badge" style="background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;padding:5px 8px;">{{ $grades[$student->id][$comp->id] ?? 0 }}</span>
                        @else
                        <input type="number"
                            class="grade-input"
                            data-student="{{ $student->id }}"
                            data-component="{{ $comp->id }}"
                            data-course="{{ $course->id }}"
                            value="{{ $grades[$student->id][$comp->id] ?? 0 }}"
                            style="width:70px;padding:5px 8px;border:1px solid var(--border);border-radius:6px;font-size:0.875rem;">
                        @endif
                    </td>
                    @endforeach
                    <td><strong>{{ $grades[$student->id]['final'] ?? 0 }}</strong></td>
                    <td>
                        @php $letter = $grades[$student->id]['letter'] ?? '-'; @endphp
                        <strong style="color: {{ $letter == 'A' ? 'var(--primary)' : ($letter == 'B' ? '#10b981' : ($letter == 'C' ? '#f59e0b' : '#ef4444')) }}">{{ $letter }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
    </div> <!-- END TAB 1 -->

    <!-- TAB 2: GRADEBOOK RINCIAN LENGKAP -->
    <div x-show="activeTab === 'gradebook'" x-cloak>
        <div class="card mb-6">
            <div class="card-header flex justify-between items-center">
                <span class="card-title">Rekap Murni (Raw Scores)</span>
                <!-- Optional: we could include the Excel export here -->
            </div>
            <div class="card-body">
                <p class="text-muted text-sm mb-4">{{ __('Tabel ini merupakan rincian skor murni per tugas dan kuis sebelum dikonversi dan dimasukkan ke Sistem Persentase.') }}</p>
                <div class="table-wrap" style="overflow-x: auto;">
                    <table class="table-striped" style="min-width: 800px;">
                        <thead>
                            <tr>
                                <th style="min-width: 150px; position: sticky; left: 0; background: #fff; z-index: 1;">Nama Siswa</th>
                                @foreach($assignmentsRaw as $assignment)
                                    <th title="{{ $assignment->title }}" class="text-center" style="min-width: 100px;">
                                        Tugas:<br>
                                        <span class="font-normal text-xs text-muted">{{ Str::limit($assignment->title, 15) }}</span>
                                    </th>
                                @endforeach
                                @foreach($quizzesRaw as $quiz)
                                    <th title="{{ $quiz->title }}" class="text-center" style="min-width: 100px;">
                                        Kuis/Ujian:<br>
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
                                    @foreach($assignmentsRaw as $assignment)
                                        <td class="text-center">
                                            @php $sc = $data['assignments'][$assignment->id]; @endphp
                                            @if($sc !== null)
                                                <span class="{{ $sc < 70 ? 'text-danger' : 'text-success' }} font-semibold">{{ $sc }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    @foreach($quizzesRaw as $quiz)
                                        <td class="text-center">
                                            @php $sc = $data['quizzes'][$quiz->id]; @endphp
                                            @if($sc !== null)
                                                <span class="{{ $sc < 70 ? 'text-danger' : 'text-success' }} font-semibold">{{ number_format($sc, 1) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-center font-bold" style="background:var(--bg-color)">
                                        {{ number_format($data['average_score'], 1) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center p-4 text-muted">Belum ada tugas/kuis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- END TAB 2 -->

</div> <!-- END TABS -->

@push('scripts')
<script>
let timeout = null;
document.querySelectorAll('.grade-input').forEach(input => {
    input.addEventListener('change', function() {
        clearTimeout(timeout);
        const el = this;
        timeout = setTimeout(() => {
            fetch('{{ route("teacher.grades.update", $course) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: el.dataset.student,
                    component_id: el.dataset.component,
                    score: el.value
                })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    el.style.borderColor = '#10b981';
                    setTimeout(() => el.style.borderColor = '', 1000);
                }
            });
        }, 500);
    });
});
</script>
@endpush
@endsection
