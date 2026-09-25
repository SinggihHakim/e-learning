@extends('layouts.app')
@section('title', __('app.my_courses'))
@section('page-title', __('app.my_courses'))

@section('content')
<div class="card mb-6">
    <div class="card-header"><span class="card-title">{{ __('app.enrolled') }} ({{ $myCourses->count() }})</span></div>
    <div class="card-body">
        @if($myCourses->isEmpty())
        <div class="empty-state">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            <h3>{{ __('app.no_courses_yet') }}</h3>
            <p>{{ __('app.no_courses_enrolled_msg') }}</p>
        </div>
        @else
        <div class="courses-grid">
            @foreach($myCourses as $course)
            <div class="course-card">
                <div class="course-card-header"></div>
                <div class="course-card-body">
                    <div class="course-card-title">{{ $course->title }}</div>
                    <p class="text-muted text-sm mb-4">{{ __('app.teacher') }}: {{ $course->teacher->name }}</p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="{{ route('student.courses.show', $course) }}" class="btn btn-sm btn-primary">{{ __('app.enter_course') }}</a>
                        <a href="{{ route('student.leaderboard', $course) }}" class="btn btn-sm btn-outline">{{ __('app.leaderboard') }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">{{ __('app.available_courses') }}</span></div>
    <div class="card-body">
        @if($availableCourses->isEmpty())
        <p class="text-muted text-center" style="padding:30px;">{{ __('app.no_courses_yet') }}</p>
        @else
        <div class="courses-grid">
            @foreach($availableCourses as $course)
            <div class="course-card" style="box-shadow:none;border-color:var(--border);">
                <div class="course-card-body">
                    <div class="course-card-title">{{ $course->title }}</div>
                    <p class="text-muted text-sm mb-4">{{ Str::limit($course->description, 70) ?: __('app.no_description') }}<br><br>{{ __('app.teacher') }}: {{ $course->teacher->name }}</p>
                    <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success w-full" style="justify-content:center;">{{ __('app.enroll_now') }}</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
