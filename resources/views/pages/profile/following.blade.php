<x-layouts.app :title="($user->profile?->display_name ?? $user->name) . ' · Mengikuti'">

<div class="mx-auto" style="max-width:600px;">
    {{-- Header --}}
    <div class="sticky-top bg-white border-bottom d-flex align-items-center gap-3 px-3 py-2" style="top:52px;z-index:89;min-height:52px;">
        <a href="{{ route('profile', $user) }}" class="text-dark fs-5 text-decoration-none">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <div class="fw-bold" style="font-size:18px;">{{ $user->profile?->display_name ?? $user->name }}</div>
            <div class="text-secondary small">{{ '@' . $user->name }}</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="d-flex border-bottom sticky-top bg-white" style="top:104px;z-index:88;">
        <a href="{{ route('profile.followers', $user) }}"
           class="flex-fill text-center py-3 fw-medium text-secondary text-decoration-none">
            Pengikut
        </a>
        <a href="{{ route('profile.following', $user) }}"
           class="flex-fill text-center py-3 fw-bold text-dark text-decoration-none position-relative">
            Mengikuti
            <span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-pill" style="height:3px;width:40px;"></span>
        </a>
    </div>

    {{-- List --}}
    @forelse($followings as $following)
        @include('components.user-row', ['user' => $following, 'authUser' => $authUser])
    @empty
        <div class="text-center py-5 text-secondary">
            <i class="ri-user-follow-line d-block fs-1 mb-3 opacity-50"></i>
            <p class="mb-0">Belum mengikuti siapapun.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($followings->hasPages())
    <div class="d-flex justify-content-center gap-2 p-3">
        @if(!$followings->onFirstPage())
            <a href="{{ $followings->previousPageUrl() }}" class="btn btn-outline-secondary btn-sm rounded-pill">‹</a>
        @endif
        @if($followings->hasMorePages())
            <a href="{{ $followings->nextPageUrl() }}" class="btn btn-outline-secondary btn-sm rounded-pill">›</a>
        @endif
    </div>
    @endif
</div>

</x-layouts.app>
