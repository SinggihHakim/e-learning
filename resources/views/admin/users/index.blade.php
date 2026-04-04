@extends('layouts.app')
@section('title', __('app.users') . ' - ' . __('app.admin'))
@section('page-title', __('app.users') . ' ' . __('app.management'))

@section('content')
<div class="flex flex-between flex-center mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search') }}..." style="width:220px;">
        <select name="role" style="width:140px;">
            <option value="">{{ __('app.role') }}</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('app.admin') }}</option>
            <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>{{ __('app.teacher') }}</option>
            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>{{ __('app.student') }}</option>
        </select>
        <button type="submit" class="btn btn-outline">{{ __('app.filter') }}</button>
    </form>
    <div class="flex gap-2">
        <a href="{{ route('admin.users.export', ['role' => request('role')]) }}" class="btn btn-outline">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Export
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            {{ __('app.add') }} {{ __('app.users') }}
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">{{ __('app.users') }} ({{ $users->total() }})</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>{{ __('app.name') }}</th><th>{{ __('app.email') }}</th><th>{{ __('app.role') }}</th><th>{{ __('app.joined') }}</th><th>{{ __('app.actions') }}</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="font-semibold">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td><span class="badge {{ $user->role === 'admin' ? 'badge-danger' : ($user->role === 'teacher' ? 'badge-warning' : 'badge-primary') }}">{{ __('app.' . $user->role) }}</span></td>
                    <td class="text-muted text-sm">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline">{{ __('app.edit') }}</a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('{{ __('app.remove_student_confirm') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><p>{{ __('app.no_students_enrolled') }}</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-body" style="padding-top:16px;">{{ $users->links() }}</div>
    @endif
</div>
@endsection
