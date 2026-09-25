@extends('layouts.app')
@section('title', __('app.attendance'))
@section('page-title', __('app.attendance'))

@section('content')
<div class="card mb-6">
    <div class="card-header"><span class="card-title">{{ __('app.active_sessions') }}</span></div>
    <div class="card-body">
        @if($activeAttendances->isEmpty())
        <div class="empty-state" style="padding:15px;">
            <p>{{ __('app.no_active_sessions') }}</p>
        </div>
        @else
        <div class="grid grid-2">
            @foreach($activeAttendances as $att)
            <div style="border:1px solid var(--border);border-radius:8px;padding:16px;">
                <div class="font-semibold">{{ $att->title }}</div>
                <div class="text-sm text-muted mt-1">{{ $att->course->title }} &bull; {{ $att->date->format('d M Y') }}</div>
                <div class="mt-2 text-sm">{{ __('app.time') }}: <strong>{{ $att->start_time }} - {{ $att->end_time }}</strong></div>

                <form method="POST" action="{{ route('student.attendance.submit', $att) }}" class="mt-4" style="display:flex;gap:8px;">
                    @csrf
                    @if($att->password)
                    <input type="text" name="password" placeholder="{{ __('app.password') }}" required style="width:100px;padding:6px;">
                    @else
                    <input type="hidden" name="password" value="">
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm" style="flex:1;">{{ __('app.submit_present') }}</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">{{ __('app.attendance_history') }}</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('app.session') }}</th><th>{{ __('app.course') }}</th><th>{{ __('app.date') }}</th><th>{{ __('app.status') }}</th><th>{{ __('app.submitted_at') }}</th></tr></thead>
            <tbody>
                @forelse($history as $record)
                <tr>
                    <td class="font-semibold">{{ $record->attendance->title }}</td>
                    <td class="text-muted">{{ $record->attendance->course->title }}</td>
                    <td>{{ $record->attendance->date->format('d M Y') }}</td>
                    <td>
                        @php
                            $statusColors = ['hadir' => 'badge-success', 'telat' => 'badge-warning', 'izin' => 'badge-purple', 'tidak_hadir' => 'badge-danger'];
                            $statusLabels = [
                                'hadir' => __('app.present'),
                                'telat' => __('app.late'),
                                'izin' => __('app.excused'),
                                'tidak_hadir' => __('app.absent'),
                            ];
                        @endphp
                        <span class="badge {{ $statusColors[$record->status] ?? 'badge-secondary' }}">{{ $statusLabels[$record->status] ?? $record->status }}</span>
                    </td>
                    <td class="text-sm text-muted">{{ $record->created_at->format('d M Y H:i:s') }}</td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state" style="padding:15px;"><p>{{ __('app.no_history') }}</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
