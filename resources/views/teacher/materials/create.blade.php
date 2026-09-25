@extends('layouts.app')
@section('title', __('app.upload_material'))
@section('page-title', __('app.upload_material'))

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><span class="card-title">{{ __('app.upload') }} {{ __('app.learning_materials') }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.materials.store') }}" enctype="multipart/form-data">
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
                <label>{{ __('app.material_title') }}</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="{{ __('app.material_title') }}...">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.type') }}</label>
                <select name="type" required>
                    <option value="">{{ __('app.select_type') }}</option>
                    <option value="pdf" {{ old('type') === 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="ppt" {{ old('type') === 'ppt' ? 'selected' : '' }}>PPT / Presentation</option>
                    <option value="doc" {{ old('type') === 'doc' ? 'selected' : '' }}>DOC / Document</option>
                    <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video Link</option>
                    <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('type')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.upload_label') }} <small class="text-muted">(PDF, PPT, DOC, ZIP - max 10MB)</small></label>
                <input type="file" name="file" accept=".pdf,.pptx,.ppt,.docx,.doc,.zip">
                @error('file')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.video_link') }} <small class="text-muted">({{ __('app.video_link_optional') }})</small></label>
                <input type="url" name="video_link" value="{{ old('video_link') }}" placeholder="https://youtube.com/...">
                @error('video_link')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.upload_material') }}</button>
                <a href="{{ isset($courseId) ? route('teacher.courses.show', $courseId) : route('teacher.materials.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
