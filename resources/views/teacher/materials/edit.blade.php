@extends('layouts.app')
@section('title', __('app.edit') . ' ' . __('app.materials'))
@section('page-title', __('app.edit') . ' ' . __('app.materials'))

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><span class="card-title">{{ __('app.edit') }} {{ __('app.materials') }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.materials.update', $material) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>{{ __('app.course') }}</label>
                <select name="course_id" required>
                    <option value="">{{ __('app.select_course_placeholder') }}</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $material->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.material_title') }}</label>
                <input type="text" name="title" value="{{ old('title', $material->title) }}" required placeholder="{{ __('app.material_title') }}...">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.type') }}</label>
                <select name="type" required>
                    <option value="">{{ __('app.select_type') }}</option>
                    <option value="pdf" {{ old('type', $material->type) === 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="ppt" {{ old('type', $material->type) === 'ppt' ? 'selected' : '' }}>PPT / Presentation</option>
                    <option value="doc" {{ old('type', $material->type) === 'doc' ? 'selected' : '' }}>DOC / Document</option>
                    <option value="video" {{ old('type', $material->type) === 'video' ? 'selected' : '' }}>Video Link</option>
                    <option value="other" {{ old('type', $material->type) === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('type')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.upload_label') }} <small class="text-muted">(PDF, PPT, DOC, ZIP - max 10MB) - Ignore if not updating file</small></label>
                @if($material->file_path)
                    <div style="margin-bottom:8px; font-size:0.85rem;">
                        Current File: <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" style="color:var(--primary); text-decoration:underline;">View File</a>
                    </div>
                @endif
                <input type="file" name="file" accept=".pdf,.pptx,.ppt,.docx,.doc,.zip">
                @error('file')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.video_link') }} <small class="text-muted">({{ __('app.video_link_optional') }})</small></label>
                <input type="url" name="video_link" value="{{ old('video_link', $material->video_link) }}" placeholder="https://youtube.com/...">
                @error('video_link')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('app.save_changes') }}</button>
                <a href="{{ route('teacher.materials.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
