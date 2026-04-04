@extends('layouts.app')
@section('title', __('app.edit') . ' ' . __('app.course'))
@section('page-title', __('app.edit') . ' ' . __('app.course'))

@section('content')
<div class="card" style="max-width:620px;">
    <div class="card-header"><span class="card-title">{{ __('app.edit') }}: {{ $course->title }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.courses.update', $course) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>{{ __('app.title') }}</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required>
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.description') }}</label>
                <textarea name="description" rows="4">{{ old('description', $course->description) }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.save_changes') }}</button>
                <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
