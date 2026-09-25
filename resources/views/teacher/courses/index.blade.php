@extends('layouts.app')
@section('title', __('app.courses'))
@section('page-title', __('app.courses'))

@section('content')
<div class="flex flex-between flex-center mb-4">
    <span class="text-muted text-sm">{{ $courses->count() }} {{ __('app.course_s') }}</span>
    <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        {{ __('app.new') }} {{ __('app.course') }}
    </a>
</div>

@if($courses->isEmpty())
<div class="card"><div class="card-body"><div class="empty-state">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
    <h3>{{ __('app.no_courses_yet') }}</h3>
    <p>{{ __('app.create_your_first_course_to_get_started') }}</p>
</div></div></div>
@else
<div class="courses-grid">
    @foreach($courses as $course)
    <div class="course-card">
        <div class="course-card-header"></div>
        <div class="course-card-body">
            <div class="course-card-title">{{ $course->title }}</div>
            <p class="text-muted text-sm mb-4">{{ Str::limit($course->description, 90) ?: __('app.no_description') }}</p>
            <div class="flex gap-2" style="flex-wrap:wrap;margin-bottom:14px;">
                <span class="badge badge-primary">{{ $course->students_count }} {{ __('app.students') }}</span>
                <span class="badge badge-secondary">{{ $course->materials_count }} {{ __('app.materials') }}</span>
                <span class="badge badge-warning">{{ $course->assignments_count }} {{ __('app.tasks') }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-sm btn-primary">{{ __('app.manage') }}</a>
                <a href="{{ route('teacher.courses.edit', $course) }}" class="btn btn-sm btn-outline">{{ __('app.edit') }}</a>
                <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('app.delete_this_course_confirmation') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
