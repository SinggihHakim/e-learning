@extends('layouts.app')
@section('title', $assignment->title . ' - ' . __('app.submissions'))
@section('page-title', __('app.submissions') . ': ' . $assignment->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('teacher.courses.show', $assignment->course_id) }}">{{ $assignment->course->title }}</a> / <span>{{ $assignment->title }}</span>
</div>

<div class="card mb-4">
    <div class="card-body" style="display:flex;gap:24px;flex-wrap:wrap;">
        <div><span class="text-muted text-sm">{{ __('app.course') }}</span><p class="font-semibold">{{ $assignment->course->title }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.deadline') }}</span><p class="font-semibold">{{ $assignment->deadline->format('d M Y H:i') }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.status') }}</span><p><span class="badge {{ $assignment->isExpired() ? 'badge-secondary' : 'badge-success' }}">{{ $assignment->isExpired() ? __('app.closed') : __('app.open') }}</span></p></div>
        <div><span class="text-muted text-sm">{{ __('app.submissions') }}</span><p class="font-semibold">{{ $submissions->count() }}</p></div>
        <div style="margin-left:auto; display:flex; gap:10px; align-items:flex-end;">
            <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="btn btn-warning">{{ __('app.edit') ?? 'Edit' }}</a>
            <form method="POST" action="{{ route('teacher.assignments.destroy', $assignment) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini? Semua data pengumpulan siswa akan ikut terhapus.')" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">{{ __('app.delete') ?? 'Hapus' }}</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">{{ __('app.student_submissions') }}</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('app.student') }}</th><th>{{ __('app.file') }}</th><th>{{ __('app.submitted') }}</th><th>{{ __('app.score') }}</th><th>{{ __('app.feedback') }}</th><th>{{ __('app.grade') }}</th></tr></thead>
            <tbody>
                @forelse($submissions as $sub)
                <tr>
                    <td class="font-semibold">{{ $sub->student->name }}</td>
                    <td>
                        @if($sub->file_path)
                        <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-sm btn-outline">{{ __('app.download') }}</a>
                        @else
                        <span class="text-muted text-sm">{{ __('app.no_file') }}</span>
                        @endif
                    </td>
                    <td class="text-muted text-sm">{{ $sub->created_at->format('d M Y H:i') }}</td>
                    <td><span class="badge {{ $sub->score !== null ? 'badge-success' : 'badge-warning' }}">{{ $sub->score !== null ? $sub->score . '/100' : __('app.not_graded_short') }}</span></td>
                    <td class="text-sm text-muted">{{ Str::limit($sub->feedback, 40) ?: '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('teacher.submissions.grade', $sub) }}" style="display:flex;gap:6px;align-items:center;">
                            @csrf
                            <input type="number" name="score" value="{{ $sub->score }}" min="0" max="100" step="0.5" style="width:70px;padding:5px 8px;">
                            <input type="text" name="feedback" value="{{ $sub->feedback }}" placeholder="{{ __('app.feedback') }}..." style="width:140px;padding:5px 8px;">
                            <button type="submit" class="btn btn-sm btn-success">{{ __('app.save') }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><p>{{ __('app.no_submissions') }}</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
