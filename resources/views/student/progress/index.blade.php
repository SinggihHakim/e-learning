@extends('layouts.app')
@section('title', __('app.learning_progress'))
@section('page-title', __('app.learning_progress'))

@section('content')
<div class="card mb-6">
    <div class="card-header"><span class="card-title">{{ __('app.course_progress') }}</span></div>
    <div class="card-body">
        @if(empty($progressData))
        <div class="empty-state">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            <p>{{ __('app.no_progress') }}</p>
        </div>
        @else
        <div class="grid grid-2">
            @foreach($progressData as $item)
            @php
                $course = $item['course'];
                $percentage = $item['percentage'];
                $completed = $item['completed_materials'];
                $total = $item['total_materials'];
            @endphp
            <div style="border:1px solid var(--border);border-radius:12px;padding:24px;">
                <h3 class="font-semibold text-lg mb-2">{{ $course->title }}</h3>
                <p class="text-sm text-muted mb-4">{{ $completed }} {{ __('app.of') }} {{ $total }} {{ __('app.materials_completed') }}</p>

                <div style="background:var(--bg);height:12px;border-radius:6px;overflow:hidden;">
                    <div style="background:var(--primary);height:100%;width:{{ $percentage }}%;transition:width 0.5s ease-in-out;"></div>
                </div>
                <div class="mt-2 text-right">
                    <span class="font-semibold" style="color:var(--primary);">{{ round($percentage) }}%</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
