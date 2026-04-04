@if($user && isset($user->profile_photo_path) && $user->profile_photo_path)
    <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name ?? 'User' }}" class="rounded-full object-cover" style="width: {{ $size }}px; height: {{ $size }}px; flex-shrink: 0;" {{ $attributes }}>
@else
    <div class="rounded-full flex items-center justify-center text-white font-bold" style="width: {{ $size }}px; height: {{ $size }}px; background-color: {{ $bgColor }}; font-size: {{ $size * 0.4 }}px; flex-shrink: 0;" {{ $attributes }}>
        {{ $initials }}
    </div>
@endif