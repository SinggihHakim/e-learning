@extends('layouts.app')
@section('title', __('app.admin') . ' ' . __('app.dashboard'))
@section('page-title', __('app.dashboard'))

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $stats['total_users'] }}</h3><p>{{ __('app.total_users') }}</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $stats['total_students'] }}</h3><p>{{ __('app.student') }}</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $stats['total_teachers'] }}</h3><p>{{ __('app.teacher') }}</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <div class="stat-info"><h3>{{ $stats['total_courses'] }}</h3><p>{{ __('app.courses') }}</p></div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title">Statistik Pengguna</span>
    </div>
    <div class="card-body" style="height: 300px; position:relative;">
        <canvas id="userRolesChart"></canvas>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('app.recent_users') }}</span>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline">{{ __('app.view_all') }}</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>{{ __('app.name') }}</th><th>{{ __('app.email') }}</th><th>{{ __('app.role') }}</th><th>{{ __('app.joined') }}</th></tr></thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td class="font-semibold">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td><span class="badge {{ $user->role === 'admin' ? 'badge-danger' : ($user->role === 'teacher' ? 'badge-warning' : 'badge-primary') }}">{{ __('app.' . $user->role) }}</span></td>
                        <td class="text-muted text-sm">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ __('app.recent_courses') }}</span>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline">{{ __('app.view_all') }}</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>{{ __('app.course') }}</th><th>{{ __('app.teacher_name') }}</th><th>{{ __('app.created_at') }}</th></tr></thead>
                <tbody>
                    @foreach($recentCourses as $course)
                    <tr>
                        <td class="font-semibold">{{ $course->title }}</td>
                        <td class="text-muted">{{ $course->teacher->name }}</td>
                        <td class="text-muted text-sm">{{ $course->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('userRolesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ __("app.student") }}', '{{ __("app.teacher") }}', '{{ __("app.admin") }}'],
                    datasets: [{
                        data: [
                            {{ $stats['total_students'] }}, 
                            {{ $stats['total_teachers'] }}, 
                            {{ $stats['total_users'] - $stats['total_students'] - $stats['total_teachers'] }}
                        ],
                        backgroundColor: [
                            '#10b981', // green for students
                            '#f59e0b', // yellow for teachers
                            '#ef4444'  // red for admin (others)
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: false }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endpush
