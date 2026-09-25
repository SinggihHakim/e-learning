@extends('layouts.app')
@section('title', __('app.student') . ' ' . __('app.dashboard'))
@section('page-title', __('app.dashboard'))

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-card-inner">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div class="stat-info"><h3>{{ $courses->count() }}</h3><p>{{ __('app.my_courses') }}</p></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent yellow"></div>
        <div class="stat-card-inner">
            <div class="stat-icon yellow">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div class="stat-info"><h3>{{ $pendingAssignments->count() }}</h3><p>{{ __('app.pending_assignments') }}</p></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent red"></div>
        <div class="stat-card-inner">
            <div class="stat-icon red">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            </div>
            <div class="stat-info"><h3>{{ $unreadCount }}</h3><p>{{ __('app.unread_notifications') }}</p></div>
        </div>
    </div>
</div>

<div class="grid grid-2">
    <div>
        <div class="card mb-4" style="border-left: 4px solid var(--warning);">
            <div class="card-header"><span class="card-title">{{ __('app.pending_assignments') }}</span></div>
            <div class="card-body">
                @if($pendingAssignments->isEmpty())
                <p class="text-muted text-sm" style="text-align:center;">{{ __('app.no_pending_assignments_msg') }}</p>
                @else
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach($pendingAssignments as $task)
                    <div style="padding:12px;background:var(--bg);border-radius:8px;">
                        <a href="{{ route('student.assignments.show', $task) }}" style="text-decoration:none;color:var(--text);font-weight:600;display:block;margin-bottom:4px;">{{ $task->title }}</a>
                        <div class="text-sm text-muted mb-2">{{ __('app.course') }}: {{ $task->course->title }}</div>
                        <div class="flex flex-between flex-center">
                             <span class="badge badge-warning">{{ __('app.due') }}: {{ $task->deadline->format('d M Y') }}</span>
                            <a href="{{ route('student.assignments.show', $task) }}" class="btn btn-sm btn-outline">{{ __('app.submit_assignment') }}</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.my_courses') }}</span></div>
            <div class="card-body">
                @if($courses->isEmpty())
                <div class="empty-state" style="padding:20px;">
                    <p>{{ __('app.no_courses_enrolled_msg') }}</p>
                    <a href="{{ route('student.courses.index') }}" class="btn btn-primary mt-4">{{ __('app.view_all') }}</a>
                </div>
                @else
                <div style="display:flex;flex-direction:column;gap:14px;">
                    @foreach($courses->take(4) as $course)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid var(--border);">
                        <div>
                            <div class="font-semibold">{{ $course->title }}</div>
                            <div class="text-muted text-sm mt-1">
                                <span>{{ $course->materials_count }} {{ __('app.materials') }}</span> &bull; <span>{{ $course->assignments_count }} {{ __('app.assignments') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('student.courses.show', $course) }}" class="btn btn-sm btn-primary">{{ __('app.go_to_class') }}</a>
                    </div>
                    @endforeach
                </div>
                <div style="text-align:center;margin-top:16px;">
                    <a href="{{ route('student.courses.index') }}" class="text-sm" style="color:var(--primary);text-decoration:none;">{{ __('app.view_all_courses', ['count' => $courses->count()]) }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="mt-6">
    <div class="card">
        <div class="card-header flex flex-between flex-center">
            <span class="card-title">{{ __('Cari Kelas Tersedia') }}</span>
            <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-outline">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if($availableCourses->isEmpty())
                <div class="empty-state" style="padding:20px;">
                    <p>{{ __('Tidak ada kelas baru yang tersedia saat ini.') }}</p>
                </div>
            @else
                <div class="grid grid-2" style="gap: 16px;">
                    @foreach($availableCourses as $course)
                    <div style="border:1px solid var(--border);border-radius:8px;padding:16px;background:var(--bg);">
                        <div class="flex flex-between">
                            <h4 class="font-semibold">{{ $course->title }}</h4>
                            <span class="badge badge-secondary" style="font-size:0.7rem;">{{ $course->materials_count }} {{ __('app.materials') }}</span>
                        </div>
                        <p class="text-sm text-muted mt-2" style="margin-bottom:12px;">Pengajar: {{ $course->teacher->name }}</p>
                        <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary w-full" style="width: 100%;">{{ __('Mendaftar Kelas') }}</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
