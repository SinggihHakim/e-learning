@extends('layouts.app')
@section('title', __('app.leaderboard') . ' - ' . $course->title)
@section('page-title', __('app.leaderboard'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('student.courses.show', $course) }}">{{ __('app.course') }}</a> / <span>{{ __('app.leaderboard') }}: {{ $course->title }}</span>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-header"><span class="card-title">{{ $course->title }} - {{ __('app.class_leaderboard') }}</span></div>
    <div class="card-body">
        @if(empty($leaderboard))
        <div class="empty-state">
            <p>{{ __('app.no_grades_leaderboard') }}</p>
        </div>
        @else
        <div class="mb-4 text-center">
            @php
                $myRank = null;
                foreach($leaderboard as $i => $entry) {
                    if($entry['student']->id === auth()->id()) {
                        $myRank = $i + 1;
                        break;
                    }
                }
            @endphp
            @if($myRank)
            <div style="background:var(--bg);display:inline-block;padding:12px 24px;border-radius:24px;font-weight:600;">
                {{ __('app.your_rank') }}: <span style="color:var(--primary);font-size:1.2rem;">#{{ $myRank }}</span>
            </div>
            @endif
        </div>

        @foreach($leaderboard as $i => $entry)
        <div class="leaderboard-item {{ $entry['student']->id === auth()->id() ? 'my-rank' : '' }}" style="{{ $entry['student']->id === auth()->id() ? 'border:2px solid var(--primary);' : '' }}">
            <div class="rank-badge {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-other')) }}">
                #{{ $i + 1 }}
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex:1;">
                <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">
                    {{ strtoupper(substr($entry['student']->name, 0, 2)) }}
                </div>
                <span class="font-semibold">{{ $entry['student']->name }} {!! $entry['student']->id === auth()->id() ? '<span class="text-xs text-muted">(Kamu)</span>' : '' !!}</span>
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
