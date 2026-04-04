@extends('layouts.app')
@section('title', __('app.courses') . ' - ' . __('app.admin'))
@section('page-title', __('app.courses') . ' ' . __('app.management'))

@section('content')
<div class="flex flex-between flex-center mb-4">
    <form method="GET" style="display:flex;gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search') }}...">
        <button type="submit" class="btn btn-outline">{{ __('app.search') }}</button>
    </form>
</div>
<div class="card">
    <div class="card-header"><span class="card-title">{{ __('app.courses') }} ({{ $courses->total() }})</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>{{ __('app.title') }}</th><th>{{ __('app.teacher_name') }}</th><th>{{ __('app.students') }}</th><th>{{ __('app.created_at') }}</th><th>{{ __('app.actions') }}</th></tr></thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td class="font-semibold">{{ $course->title }}</td>
                    <td class="text-muted">{{ $course->teacher->name }}</td>
                    <td>{{ $course->students()->count() }}</td>
                    <td class="text-muted text-sm">{{ $course->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline">{{ __('app.view_details') }}</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('app.delete_this_course_confirmation') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><p>{{ __('app.no_courses_yet') }}</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($courses->hasPages())
    <div class="card-body">{{ $courses->links() }}</div>
    @endif
</div>
@endsection
