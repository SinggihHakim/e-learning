@extends('layouts.app')
@section('title', __('app.edit') . ' ' . __('app.attendance'))
@section('page-title', __('app.edit') . ' ' . __('app.attendance'))

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><span class="card-title">{{ __('app.edit') }} {{ __('app.session') }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.attendance.update', $attendance) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>{{ __('app.course') }}</label>
                <select name="course_id" required>
                    <option value="">{{ __('app.select_course_placeholder') }}</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $attendance->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.session_title') }}</label>
                <input type="text" name="title" value="{{ old('title', $attendance->title) }}" required placeholder="{{ __('app.session_title') }}...">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.date') }}</label>
                <input type="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                @error('date')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label>{{ __('app.start_time') }}</label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($attendance->start_time)->format('H:i')) }}" required>
                    @error('start_time')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>{{ __('app.end_time') }}</label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($attendance->end_time)->format('H:i')) }}" required>
                    @error('end_time')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="form-group">
                <label>{{ __('app.attendance_password') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                <input type="text" name="password" value="{{ old('password', $attendance->password) }}" placeholder="{{ __('app.no_password_required') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.save_changes') }}</button>
                <a href="{{ route('teacher.attendance.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
