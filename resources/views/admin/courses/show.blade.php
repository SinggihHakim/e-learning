@extends('layouts.app')
@section('title', $course->title)
@section('page-title', $course->title)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.courses.index') }}">{{ __('app.courses') }}</a> / <span>{{ $course->title }}</span>
</div>

<div class="grid grid-2">
    <div>
        <div class="card mb-6">
            <div class="card-header"><span class="card-title">{{ __('app.about') }} {{ __('app.course') }}</span></div>
            <div class="card-body">
                <p class="text-muted">{{ $course->description ?: __('app.no_description') }}</p>
                <div class="mt-4 pt-4" style="border-top:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <span class="text-sm text-muted">{{ __('app.teacher_name') }}</span>
                        <span class="font-semibold">{{ $course->teacher->name }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <span class="text-sm text-muted">{{ __('app.created_at') }}</span>
                        <span class="font-semibold">{{ $course->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.students_enrolled') }} ({{ $course->students->count() }})</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>{{ __('app.name') }}</th><th>{{ __('app.email') }}</th></tr></thead>
                    <tbody>
                        @forelse($course->students as $student)
                        <tr>
                            <td class="font-semibold">{{ $student->name }}</td>
                            <td class="text-muted">{{ $student->email }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2"><div class="empty-state"><p>{{ __('app.no_students_enrolled') }}</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="card mb-6">
            <div class="card-header"><span class="card-title">{{ __('app.learning_materials') }} ({{ $course->materials->count() }})</span></div>
            <div class="table-wrap">
                <table>
                    <tbody>
                        @forelse($course->materials as $mat)
                        <tr>
                            <td class="font-semibold">{{ $mat->title }}</td>
                            <td class="text-right"><span class="badge badge-secondary">{{ strtoupper($mat->type) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="2"><div class="empty-state" style="padding:15px;"><p>{{ __('app.no_materials_yet') }}</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.assignments') }} ({{ $course->assignments->count() }})</span></div>
            <div class="table-wrap">
                <table>
                    <tbody>
                        @forelse($course->assignments as $asgn)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $asgn->title }}</div>
                                <div class="text-muted text-sm">{{ __('app.due') }}: {{ $asgn->deadline->format('d M Y') }}</div>
                            </td>
                            <td class="text-right">
                                <span class="badge {{ $asgn->isExpired() ? 'badge-danger' : 'badge-primary' }}">
                                    {{ $asgn->isExpired() ? __('app.closed') : __('app.open') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2"><div class="empty-state" style="padding:15px;"><p>{{ __('app.no_assignments_yet') }}</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
