@extends('layouts.app')
@section('title', $assignment->title)
@section('page-title', __('app.assignment_info'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('student.courses.show', $assignment->course_id) }}">{{ $assignment->course->title }}</a> / <span>{{ $assignment->title }}</span>
</div>

<div class="grid grid-2">
    <div>
        <div class="card mb-4" style="border-top: 4px solid var(--primary);">
            <div class="card-header"><span class="card-title">{{ $assignment->title }}</span></div>
            <div class="card-body">
                <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:16px;">
                    <div><span class="text-sm text-muted">{{ __('app.course') }}</span><p class="font-semibold">{{ $assignment->course->title }}</p></div>
                    <div>
                        <span class="text-sm text-muted">{{ __('app.deadline') }}</span>
                        <p class="font-semibold {{ $assignment->isExpired() ? 'text-danger' : '' }}">{{ $assignment->deadline->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div class="text-muted" style="white-space:pre-wrap;background:var(--bg);padding:12px;border-radius:6px;">{{ $assignment->description ?: __('app.no_description') }}</div>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header"><span class="card-title">{{ __('app.your_submission') }}</span></div>
            <div class="card-body">
                @if($submission)
                <div style="background:var(--bg);padding:16px;border-radius:8px;">
                    <p class="mb-4"><strong>{{ __('app.status') }}:</strong> <span class="badge badge-success">{{ __('app.submitted_on') }} {{ $submission->created_at->format('d M Y, H:i') }}</span></p>

                    @if($submission->file_path)
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-primary font-semibold">{{ __('app.view_uploaded_file') }}</a>
                    </div>
                    @endif

                    @if($submission->score !== null)
                    <div style="padding-top:16px;border-top:1px solid var(--border);">
                        <p><strong>{{ __('app.grade') }}:</strong> <span class="badge badge-primary" style="font-size:1rem;">{{ $submission->score }} / 100</span></p>
                        <p class="mt-2 text-sm text-muted"><strong>{{ __('app.feedback') }}:</strong> {{ $submission->feedback ?: __('app.no_feedback') }}</p>
                    </div>
                    @else
                    <p class="text-sm text-muted" style="padding-top:16px;border-top:1px solid var(--border);">{{ __('app.not_graded') }}</p>
                    @endif
                </div>
                @else
                    @if($assignment->isExpired())
                    <div class="empty-state" style="padding:20px;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <h4 class="text-danger mt-2">{{ __('app.deadline_passed') }}</h4>
                        <p class="mt-1 text-sm text-muted">{{ __('app.deadline_passed_msg') }}</p>
                    </div>
                    @else
                    <form method="POST" action="{{ route('student.assignments.submit', $assignment) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label style="font-weight:600;margin-bottom:8px;">{{ __('app.upload_file') }} <span class="text-muted" style="font-weight:normal;">(PDF, DOCX, ZIP, PPTX - Max 10MB)</span></label>
                            <input type="file" name="file" required accept=".pdf,.doc,.docx,.zip,.rar,.ppt,.pptx" style="padding:8px;">
                            @error('file')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-full">{{ __('app.submit_assignment') }}</button>
                    </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
