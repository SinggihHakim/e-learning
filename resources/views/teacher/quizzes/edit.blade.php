@extends('layouts.app')
@section('title', 'Edit Kuis')
@section('page-title', 'Edit Kuis')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><span class="card-title">Edit Kuis: {{ $quiz->title }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.quizzes.update', $quiz) }}">
            @csrf @method('PUT')
            
            <div class="form-group">
                <label>Judul Kuis</label>
                <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required>
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Deskripsi/Petunjuk</label>
                <textarea name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Durasi (Menit)</label>
                <input type="number" name="duration" value="{{ old('duration', $quiz->duration) }}" min="1">
                @error('duration')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Komponen Penilaian (Opsional)</label>
                <select name="component_id">
                    <option value="">-- Pilih Komponen (UAS, UTS, dll) --</option>
                    @foreach($components as $comp)
                    <option value="{{ $comp->id }}" {{ old('component_id', $quiz->component_id) == $comp->id ? 'selected' : '' }}>{{ $comp->course->title }} - {{ $comp->name }} ({{ $comp->weight }}%)</option>
                    @endforeach
                </select>
                <small class="text-muted">Jika dipilih, nilai kuis otomatis masuk ke rekap nilai akhir untuk komponen ini.</small>
                @error('component_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
                <a href="{{ route('teacher.courses.show', $quiz->course_id) }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
