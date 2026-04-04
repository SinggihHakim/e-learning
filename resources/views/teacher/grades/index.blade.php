@extends('layouts.app')
@section('title', __('app.grades'))
@section('page-title', __('app.grades'))

@section('content')
<div class="card">
    <div class="card-header"><span class="card-title">{{ __('app.select_course') }}</span></div>
    <div class="card-body">
        @foreach($courses as $course)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);">
            <div>
                <p class="font-semibold">{{ $course->title }}</p>
                <p class="text-muted text-sm">{{ $course->students()->count() }} {{ __('app.students_enrolled') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('teacher.grades.show', $course) }}" class="btn btn-sm btn-primary">{{ __('app.manage_grades') }}</a>
                <a href="{{ route('teacher.grades.export', $course) }}" class="btn btn-sm btn-success">{{ __('app.export_excel') }}</a>
                <a href="{{ route('teacher.leaderboard', $course) }}" class="btn btn-sm btn-outline">{{ __('app.leaderboard') }}</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
