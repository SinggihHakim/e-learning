@extends('layouts.app')
@section('title', __('app.leaderboard') . ' - ' . $course->title)
@section('page-title', __('app.leaderboard'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('teacher.grades.index') }}">{{ __('app.grades') }}</a> / <span>{{ __('app.leaderboard') }}: {{ $course->title }}</span>
</div>
<div class="card" style="max-width:700px;">
    <div class="card-header">
        <span class="card-title">{{ $course->title }} - {{ __('app.student_rankings') }}</span>
        <a href="{{ route('teacher.grades.export', $course) }}" class="btn btn-sm btn-success">{{ __('app.export') }}</a>
    </div>
    <div class="card-body">
        @if(empty($leaderboard) || count($leaderboard) === 0)
        <div class="empty-state">
            <p>{{ __('app.no_grades_leaderboard') }}</p>
        </div>
        @else
        @foreach($leaderboard as $i => $entry)
        <div class="leaderboard-item">
            <div class="rank-badge {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-other')) }}">
                #{{ $i + 1 }}
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex:1;">
                <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">
                    {{ strtoupper(substr($entry['student']->name, 0, 2)) }}
                </div>
                <span class="font-semibold">{{ $entry['student']->name }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:1.1rem;font-weight:700;color:var(--primary);">{{ $entry['score'] }}</span>
                <span class="text-muted text-sm"> pts</span>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection
