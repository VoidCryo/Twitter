@php $profile = $user->profile; @endphp
<div class="d-flex align-items-center gap-3 px-3 py-3 border-bottom">
    <a href="{{ route('profile', $user) }}" class="flex-shrink-0">
        @if($profile?->avatar)
            <img src="{{ Storage::url($profile->avatar) }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
        @else
            <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-secondary" style="width:40px;height:40px;">{{ strtoupper(substr($user->profile?->display_name ?? $user->name, 0, 1)) }}</div>
        @endif
    </a>
    <div class="flex-grow-1 min-w-0">
        <a href="{{ route('profile', $user) }}" class="d-block text-decoration-none">
            <div class="fw-semibold text-dark text-truncate" style="font-size:15px;">{{ $user->profile?->display_name ?? $user->name }}</div>
            <div class="text-secondary small">{{ '@' . $user->name }}</div>
        </a>
        @if($profile?->bio)
            <div class="text-dark text-truncate small mt-1">{{ Str::limit($profile->bio, 80) }}</div>
        @endif
    </div>
    @if(auth()->check() && $authUser->id !== $user->id)
    <form action="{{ route('follow.toggle', $user) }}" method="POST" class="flex-shrink-0">
        @csrf
        @if($authUser->isFollowing($user))
            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">Mengikuti</button>
        @else
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">Ikuti</button>
        @endif
    </form>
    @endif
</div>
