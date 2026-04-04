@extends('layouts.app')
@section('title', __('app.create_assignment'))
@section('page-title', __('app.create_assignment'))

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><span class="card-title">{{ __('app.new') }} {{ __('app.assignments') }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.assignments.store') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('app.course') }}</label>
                <select name="course_id" required>
                    <option value="">{{ __('app.select_course_placeholder') }}</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (old('course_id') ?? ($courseId ?? '')) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.assignment_title') }}</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.instructions') }}</label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label>{{ __('app.deadline') }}</label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required>
                @error('deadline')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>Komponen Penilaian (Opsional)</label>
                <select name="component_id">
                    <option value="">-- Pilih Komponen (UAS, UTS, dll) --</option>
                    @foreach($components as $comp)
                    <!-- Only show if it matches the selected course (or we can just show all for simplicity with JS, but server side validated) -->
                    <option value="{{ $comp->id }}" {{ old('component_id') == $comp->id ? 'selected' : '' }}>{{ $comp->course->title }} - {{ $comp->name }} ({{ $comp->weight }}%)</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('Jika dipilih, nilai tugas otomatis masuk ke rekap nilai akhir untuk komponen ini.') }}</small>
                @error('component_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.create') }}</button>
                <a href="{{ isset($courseId) ? route('teacher.courses.show', $courseId) : route('teacher.courses.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
