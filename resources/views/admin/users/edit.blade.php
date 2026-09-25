@extends('layouts.app')
@section('title', __('app.edit') . ' ' . __('app.users'))
@section('page-title', __('app.edit') . ' ' . __('app.users'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.users.index') }}">{{ __('app.users') }}</a> / <span>{{ __('app.edit') }}: {{ $user->name }}</span>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-header"><span class="card-title">{{ __('app.edit') }} {{ __('app.users') }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>{{ __('app.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.email') }}</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.role') }}</label>
                <select name="role" required>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>{{ __('app.admin') }}</option>
                    <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>{{ __('app.teacher') }}</option>
                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>{{ __('app.student') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>{{ __('app.new_password') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                <input type="password" name="password">
            </div>
            <div class="form-group">
                <label>{{ __('app.confirm_password') }}</label>
                <input type="password" name="password_confirmation">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.save_changes') }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>

<div class="card mt-6" style="max-width:600px; margin-top:20px;">
    <div class="card-header"><span class="card-title">{{ __('app.bio') }}</span></div>
    <div class="card-body">
        <table style="width:100%; text-align:left; border-collapse:collapse;">
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border); width:40%;">NIS / NIP</th><td style="border-bottom:1px solid var(--border);">{{ $user->nis ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Jenis Kelamin</th><td style="border-bottom:1px solid var(--border);">{{ $user->gender ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Tempat Lahir</th><td style="border-bottom:1px solid var(--border);">{{ $user->birth_place ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Tanggal Lahir</th><td style="border-bottom:1px solid var(--border);">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d F Y') : '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Alamat Tempat Tinggal</th><td style="border-bottom:1px solid var(--border);">{{ $user->address ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Agama</th><td style="border-bottom:1px solid var(--border);">{{ $user->religion ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">No. Telepon/Hp</th><td style="border-bottom:1px solid var(--border);">{{ $user->phone ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Nama Ayah</th><td style="border-bottom:1px solid var(--border);">{{ $user->father_name ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Nama Ibu</th><td style="border-bottom:1px solid var(--border);">{{ $user->mother_name ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Pekerjaan Ayah</th><td style="border-bottom:1px solid var(--border);">{{ $user->father_job ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0; border-bottom:1px solid var(--border);">Pekerjaan Ibu</th><td style="border-bottom:1px solid var(--border);">{{ $user->mother_job ?: '-' }}</td></tr>
            <tr><th style="padding:8px 0;">Alamat Orang Tua</th><td>{{ $user->parent_address ?: '-' }}</td></tr>
        </table>
    </div>
</div>
@endsection
