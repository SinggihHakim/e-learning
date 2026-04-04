@extends('layouts.app')
@section('title', __('app.teacher') . ' ' . __('app.dashboard'))
@section('page-title', __('app.dashboard'))

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $courses->count() }}</h3><p>{{ __('app.my_courses') }}</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $totalStudents }}</h3><p>{{ __('app.total_students') }}</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $totalAssignments }}</h3><p>{{ __('app.assignments') }}</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $pendingGrading }}</h3><p>{{ __('app.pending_grading') }}</p></div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">{{ __('app.my_courses') }}</span>
        <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary btn-sm">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            {{ __('app.new') }} {{ __('app.course') }}
        </a>
    </div>
    <div class="card-body">
        @if($courses->isEmpty())
        <div class="empty-state">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            <h3>{{ __('app.no_courses_yet') }}</h3>
            <p>{{ __('app.create_your_first_course_to_get_started') }}</p>
        </div>
        @else
        <div class="courses-grid">
            @foreach($courses as $course)
            <div class="course-card">
                <div class="course-card-header"></div>
                <div class="course-card-body">
                    <div class="course-card-title">{{ $course->title }}</div>
                    <p class="text-muted text-sm mb-4">{{ Str::limit($course->description, 80) ?: __('app.no_description') }}</p>
                    <div class="flex gap-2" style="flex-wrap:wrap;margin-bottom:12px;">
                        <span class="badge badge-primary">{{ $course->students_count }} {{ __('app.students') }}</span>
                        <span class="badge badge-secondary">{{ $course->materials_count }} {{ __('app.materials') }}</span>
                        <span class="badge badge-warning">{{ $course->assignments_count }} {{ __('app.tasks') }}</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-sm btn-primary">{{ __('app.manage') }}</a>
                        <a href="{{ route('teacher.grades.show', $course) }}" class="btn btn-sm btn-outline">{{ __('app.grades') }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
