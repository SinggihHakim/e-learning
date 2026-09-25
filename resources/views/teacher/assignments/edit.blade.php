@extends('layouts.app')
@section('title', 'Edit Tugas')
@section('page-title', 'Edit Tugas')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><span class="card-title">Edit Tugas: {{ $assignment->title }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.assignments.update', $assignment) }}">
            @csrf @method('PUT')
            
            <div class="form-group">
                <label>{{ __('app.assignment_title') }}</label>
                <input type="text" name="title" value="{{ old('title', $assignment->title) }}" required>
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>{{ __('app.instructions') }}</label>
                <textarea name="description" rows="4">{{ old('description', $assignment->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>{{ __('app.deadline') }}</label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline', $assignment->deadline ? $assignment->deadline->format('Y-m-d\TH:i') : '') }}" required>
                @error('deadline')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Komponen Penilaian (Opsional)</label>
                <select name="component_id">
                    <option value="">-- Pilih Komponen (UAS, UTS, dll) --</option>
                    @foreach($components as $comp)
                    <option value="{{ $comp->id }}" {{ old('component_id', $assignment->component_id) == $comp->id ? 'selected' : '' }}>{{ $comp->course->title }} - {{ $comp->name }} ({{ $comp->weight }}%)</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('Jika dipilih, nilai tugas otomatis masuk ke rekap nilai akhir untuk komponen ini.') }}</small>
                @error('component_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
                <a href="{{ route('teacher.courses.show', $assignment->course_id) }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
