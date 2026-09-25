@extends('layouts.app')
@section('title', __('app.grades'))
@section('page-title', __('app.grades'))

@section('content')
<div class="card">
    <div class="card-header"><span class="card-title">{{ __('app.course_grades') }}</span></div>
    <div class="card-body">
        @if(empty($courseGrades))
        <div class="empty-state">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            <p>{{ __('app.no_grades') }}</p>
        </div>
        @else
        <div class="grid grid-2">
            @foreach($courseGrades as $data)
            <div style="border:1px solid var(--border);border-radius:12px;padding:20px;">
                <h3 class="font-semibold text-lg mb-4">{{ $data['course']->title }}</h3>

                <div style="margin-bottom:16px;">
                    @foreach($data['components'] as $comp)
                        @php $score = $data['grades']->where('component_id', $comp->id)->first(); @endphp
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bg-hover);">
                            <span class="text-muted">{{ $comp->name }} ({{ $comp->weight }}%)</span>
                            <span class="font-semibold">{{ $score ? $score->score : '-' }}</span>
                        </div>
                    @endforeach
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;background:var(--bg);padding:12px;border-radius:8px;">
                    <span class="font-semibold">{{ __('app.final_score') }}</span>
                    <span class="badge badge-success" style="font-size:1.1rem;">{{ $data['final_score'] }}</span>
                </div>

                <div class="mt-4" style="text-align:right;">
                    <a href="{{ route('student.leaderboard', $data['course']) }}" class="btn btn-sm btn-outline">{{ __('app.leaderboard') }}</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
