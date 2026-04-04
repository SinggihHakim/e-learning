@extends('layouts.app')
@section('title', 'Buat Kuis Baru')
@section('page-title', 'Buat Kuis')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><span class="card-title">Buat Kuis Baru</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.quizzes.store') }}">
            @csrf
            
            <div class="form-group">
                <label>{{ __('app.course') }}</label>
                <select name="course_id" required>
                    <option value="">{{ __('app.select_course_placeholder') ?? 'Pilih Kelas' }}</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (old('course_id') ?? ($courseId ?? '')) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Judul Kuis</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Ujian Tengah Semester">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Deskripsi/Petunjuk Kuis</label>
                <textarea name="description" rows="3" placeholder="Opsional: Kerjakan dengan jujur.">{{ old('description') }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Durasi (Menit)</label>
                <input type="number" name="duration" value="{{ old('duration') }}" min="1" placeholder="Opsional, biarkan kosong jika tanpa waktu">
                <small class="text-muted">Jika diisi, kuis akan otomatis ditutup setelah waktu habis.</small>
                @error('duration')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Komponen Penilaian (Opsional)</label>
                <select name="component_id">
                    <option value="">-- Pilih Komponen (UAS, UTS, dll) --</option>
                    @foreach($components as $comp)
                    <option value="{{ $comp->id }}" {{ old('component_id') == $comp->id ? 'selected' : '' }}>{{ $comp->course->title }} - {{ $comp->name }} ({{ $comp->weight }}%)</option>
                    @endforeach
                </select>
                <small class="text-muted">Jika dipilih, nilai kuis otomatis masuk ke rekap nilai akhir untuk komponen ini.</small>
                @error('component_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">{{ __('app.create') ?? 'Buat' }} Kuis</button>
                <a href="{{ isset($courseId) ? route('teacher.courses.show', $courseId) : route('teacher.courses.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
