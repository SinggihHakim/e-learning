@extends('layouts.app')
@section('title', $material->title)
@section('page-title', $material->title)

@section('content')
<div class="breadcrumb mb-4">
    <a href="{{ route('student.courses.show', $material->course_id) }}">{{ __('app.materials') }}</a> / <span>{{ $material->title }}</span>
</div>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">{{ __('app.course') }}: {{ $material->course->title }}</span></div>
    <div class="card-body">
        <h2 class="font-semibold text-xl mb-4">{{ $material->title }}</h2>
        <div class="flex gap-2 mb-4">
            <span class="badge badge-secondary">{{ strtoupper($material->type) }}</span>
            <span class="text-muted text-sm">{{ $material->created_at->format('d M Y H:i') }}</span>
        </div>
        
        <div style="margin-top: 20px; padding: 20px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; display: flex; flex-direction: column;">
            <div style="width: 100%;">
                @if($material->video_link)
                    @php
                        $embedUrl = $material->video_link;
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $embedUrl, $match);
                        if (isset($match[1])) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $match[1];
                        }
                    @endphp
                    <div style="margin-bottom: 20px;">
                        <iframe width="100%" height="400" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border-radius: 8px;"></iframe>
                        <p style="margin-top:10px;"><a href="{{ $material->video_link }}" target="_blank" style="color:var(--primary); text-decoration:underline;">Buka link asli video</a></p>
                    </div>
                @endif

                @if($material->file_path)
                <p class="mb-2">{{ __('app.download') }} file materi pendukung:</p>
                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-primary">{{ __('app.download') }}</a>
                @elseif(!$material->video_link)
                <p class="text-muted">Tidak ada lampiran.</p>
                @endif
            </div>
            
            <div style="margin-top: 20px; display: flex; justify-content: flex-end; align-items: center; border-top: 1px solid var(--border); padding-top: 15px;">
                @php $isCompleted = \App\Models\Progress::where('student_id', auth()->id())->where('material_id', $material->id)->where('completed', true)->exists(); @endphp
                @if(!$isCompleted)
                <form method="POST" action="{{ route('student.materials.complete', $material) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">{{ __('app.mark_done') }}</button>
                </form>
                @else
                <span class="badge badge-success text-lg" style="padding: 10px 15px;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px; display:inline-block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Selesai Dibaca</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header"><span class="card-title">Forum Diskusi Kelas</span></div>
    <div class="card-body">
        <div class="comments-section" style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
            @forelse($material->comments as $comment)
            <div style="padding: 15px; border: 1px solid var(--border); background: {{ $comment->is_pinned ? '#fffbeb' : '#fff' }}; border-radius: 8px; margin-bottom: 12px; position: relative;" x-data="{ replyOpen: false }">
                @if($comment->is_pinned)
                    <div style="position: absolute; top: -10px; right: 15px; background: #fbbf24; color: #78350f; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; display: flex; align-items: center; gap: 4px;">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" /></svg> Disematkan
                    </div>
                @endif
                <div class="flex gap-3 mb-3">
                    <x-avatar :user="$comment->user" size="40" />
                    <div class="flex-1">
                        <div class="flex flex-between">
                            <span class="font-semibold" style="color: var(--primary);">{{ $comment->user->name }} <span class="badge badge-secondary" style="font-size:0.65rem; margin-left:4px;">Lvl {{ $comment->user->level ?? 1 }}</span></span>
                            <span class="text-muted text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="mt-2 text-sm" style="line-height: 1.5;">{!! strip_tags($comment->comment, '<p><br><b><i><u><ul><ol><li><a><strong><em>') !!}</div>
                        <div class="flex gap-2 mt-3 items-center">
                            <button @click="replyOpen = !replyOpen" class="btn btn-sm btn-outline" style="font-size: 0.75rem; padding: 4px 8px;">Balas</button>
                            @if($comment->user_id === auth()->id() && $comment->created_at->diffInMinutes(now()) <= 60)
                            <form method="POST" action="{{ route('student.materials.comments.destroy', $comment) }}" onsubmit="return confirm('Hapus komentar ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline" style="font-size: 0.75rem; padding: 4px 8px; color: var(--danger); border-color: #fecaca;">Hapus ({{ 60 - $comment->created_at->diffInMinutes(now()) }}m)</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Balas -->
                <div x-show="replyOpen" x-transition class="mt-3 pl-12" style="display: none;">
                    <form method="POST" action="{{ route('student.materials.comment', $material) }}">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div class="form-group mb-2">
                            <input type="text" name="comment" placeholder="Tulis balasan..." required style="width: 100%; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">Kirim Balasan</button>
                            <button type="button" @click="replyOpen = false" class="btn btn-outline btn-sm">{{ __('Batal') }}</button>
                        </div>
                    </form>
                </div>

                <!-- Nested Replies -->
                @if($comment->replies->count() > 0)
                    <div class="pl-8 mt-4 border-l-2" style="margin-left: 20px; border-left-color: #e2e8f0; padding-left: 15px;">
                        @foreach($comment->replies as $reply)
                            <div class="mb-3">
                                <div class="flex gap-3 mb-1">
                                    <x-avatar :user="$reply->user" size="32" />
                                    <div class="flex-1">
                                        <div class="flex gap-2 items-center flex-between">
                                            <div>
                                                <span class="font-semibold text-sm">{{ $reply->user->name }}</span>
                                                <span class="text-muted text-xs ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($reply->user_id === auth()->id() && $reply->created_at->diffInMinutes(now()) <= 60)
                                            <form method="POST" action="{{ route('student.materials.comments.destroy', $reply) }}" onsubmit="return confirm('Hapus balasan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background:none; border:none; color:var(--danger); cursor:pointer; font-size: 0.7rem;">&times; Hapus</button>
                                            </form>
                                            @endif
                                        </div>
                                        <div class="mt-1 text-sm bg-gray-50 p-2 rounded" style="background: #f8fafc;">{!! strip_tags($reply->comment, '<p><br><b><i><u><ul><ol><li><a><strong><em>') !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @empty
            <div class="empty-state">
                <p class="text-muted">Belum ada diskusi untuk materi ini.</p>
            </div>
            @endforelse
        </div>

        <form method="POST" action="{{ route('student.materials.comment', $material) }}" style="margin-top: 20px;" id="commentForm">
            @csrf
            @error('comment')
                <div class="alert alert-danger" style="color:var(--danger); font-size: 0.85rem; margin-bottom: 5px;">{{ $message }}</div>
            @enderror
            <div class="form-group border rounded" style="background:#fff;">
                <div id="editor-container" style="min-height: 120px; border:none;"></div>
                <input type="hidden" name="comment" id="comment-input" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Kirim Diskusi</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Quill Editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Tanyakan sesuatu atau berikan tanggapan...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link']
            ]
        }
    });

    var form = document.getElementById('commentForm');
    form.onsubmit = function() {
        var content = document.getElementById('comment-input');
        var text = quill.getText().trim();
        if (text.length === 0) {
            alert('Komentar tidak boleh kosong');
            return false;
        }
        content.value = quill.root.innerHTML;
    };
</script>
@endpush

@endsection
