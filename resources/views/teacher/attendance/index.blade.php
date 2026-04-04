@extends('layouts.app')
@section('title', __('app.attendance'))
@section('page-title', __('app.attendance'))

@section('content')
<div class="flex flex-between flex-center mb-4">
    <span class="text-muted text-sm">{{ $attendances->count() }} {{ __('app.session') }}</span>
    <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        {{ __('app.new') }} {{ __('app.session') }}
    </a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('app.title') }}</th><th>{{ __('app.course') }}</th><th>{{ __('app.date') }}</th><th>{{ __('app.time') }}</th><th>{{ __('app.responses') }}</th><th>{{ __('app.actions') }}</th></tr></thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td class="font-semibold">{{ $att->title }}</td>
                    <td class="text-muted">{{ $att->course->title }}</td>
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td class="text-muted text-sm">{{ $att->start_time }} - {{ $att->end_time }}</td>
                    <td><span class="badge badge-primary">{{ $att->attendance_students_count }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('teacher.attendance.show', $att) }}" class="btn btn-sm btn-primary">{{ __('app.details') }}</a>
                            <form method="POST" action="{{ route('teacher.attendance.destroy', $att) }}" onsubmit="return confirm('{{ __('app.delete_assignment_confirm') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><p>{{ __('app.no_history') }}</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
