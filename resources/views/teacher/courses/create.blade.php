@extends('layouts.app')
@section('title', __('app.new_course'))
@section('page-title', __('app.new_course'))

@section('content')
<div class="card" style="max-width:620px;">
    <div class="card-header"><span class="card-title">{{ __('app.new_course') }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.courses.store') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('app.title') }}</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Introduction to Programming">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.description') }}</label>
                <textarea name="description" rows="4" placeholder="{{ __('app.instructions') }}...">{{ old('description') }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
