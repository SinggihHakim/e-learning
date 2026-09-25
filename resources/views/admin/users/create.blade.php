@extends('layouts.app')
@section('title', __('app.add') . ' ' . __('app.users'))
@section('page-title', __('app.add') . ' ' . __('app.users'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.users.index') }}">{{ __('app.users') }}</a> / <span>{{ __('app.add') }}</span>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-header"><span class="card-title">{{ __('app.new') }} {{ __('app.users') }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('app.name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.role') }}</label>
                <select name="role" required>
                    <option value="">{{ __('app.role') }}...</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('app.admin') }}</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>{{ __('app.teacher') }}</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>{{ __('app.student') }}</option>
                </select>
                @error('role')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.password') }}</label>
                <input type="password" name="password" required>
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.confirm_password') }}</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.add') }} {{ __('app.users') }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
