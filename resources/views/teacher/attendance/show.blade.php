@extends('layouts.app')
@section('title', $attendance->title)
@section('page-title', __('app.attendance') . ': ' . $attendance->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('teacher.attendance.index') }}">{{ __('app.attendance') }}</a> / <span>{{ $attendance->title }}</span>
</div>
<div class="card mb-4">
    <div class="card-body" style="display:flex;gap:24px;flex-wrap:wrap;">
        <div><span class="text-muted text-sm">{{ __('app.course') }}</span><p class="font-semibold">{{ $attendance->course->title }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.date') }}</span><p class="font-semibold">{{ $attendance->date->format('d M Y') }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.time') }}</span><p class="font-semibold">{{ $attendance->start_time }} - {{ $attendance->end_time }}</p></div>
        <div><span class="text-muted text-sm">{{ __('app.attendance_password') }}</span><p class="font-semibold">{{ $attendance->password ?: __('app.no_password') }}</p></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">{{ __('app.student_records') }} ({{ $enrolled->count() }} {{ __('app.students_enrolled') }})</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('app.student') }}</th><th>{{ __('app.status') }}</th></tr></thead>
            <tbody>
                @foreach($enrolled as $student)
                @php $record = $submissions[$student->id] ?? null; @endphp
                <tr>
                    <td class="font-semibold">{{ $student->name }}</td>
                    <td>
                        @if($record)
                            @php
                                $statusColors = ['hadir' => 'badge-success', 'telat' => 'badge-warning', 'izin' => 'badge-purple', 'tidak_hadir' => 'badge-danger'];
                                $statusLabels = [
                                    'hadir' => __('app.present'),
                                    'telat' => __('app.late'),
                                    'izin' => __('app.excused'),
                                    'tidak_hadir' => __('app.absent'),
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$record->status] ?? 'badge-secondary' }}">
                                {{ $statusLabels[$record->status] ?? $record->status }}
                            </span>
                        @else
                            <span class="badge badge-secondary">{{ __('app.not_recorded') }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
