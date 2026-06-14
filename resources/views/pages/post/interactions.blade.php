<x-layouts.app title="Interaksi · Tenebris">

<div class="mx-auto" style="max-width:600px;">
    {{-- Header --}}
    <div class="sticky-top bg-white border-bottom d-flex align-items-center gap-3 px-3 py-2" style="top:52px;z-index:89;min-height:52px;">
        <a href="{{ url()->previous() }}" class="text-dark fs-5 text-decoration-none">
            <i class="ri-arrow-left-line"></i>
        </a>
        <span class="fw-bold fs-5">Interaksi</span>
    </div>

    {{-- Tabs --}}
    <div class="d-flex border-bottom sticky-top bg-white" style="top:104px;z-index:88;">
        <a href="{{ route('post.interactions', ['post' => $post, 'tab' => 'likes']) }}"
           class="flex-fill text-center py-3 text-decoration-none position-relative {{ $tab !== 'reposts' ? 'fw-bold text-dark' : 'fw-medium text-secondary' }}">
            Suka · {{ $post->liked_by_count ?? 0 }}
            @if($tab !== 'reposts')<span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-pill" style="height:3px;width:40px;"></span>@endif
        </a>
        <a href="{{ route('post.interactions', ['post' => $post, 'tab' => 'reposts']) }}"
           class="flex-fill text-center py-3 text-decoration-none position-relative {{ $tab === 'reposts' ? 'fw-bold text-dark' : 'fw-medium text-secondary' }}">
            Repost · {{ $post->reposts_count ?? 0 }}
            @if($tab === 'reposts')<span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-pill" style="height:3px;width:40px;"></span>@endif
        </a>
    </div>

    {{-- User list --}}
    @if($tab === 'reposts')
        @forelse($reposters as $user)
            @include('components.user-row', ['user' => $user, 'authUser' => $authUser])
        @empty
            <div class="text-center py-5 text-secondary">
                <i class="ri-repeat-2-line d-block fs-1 mb-3 opacity-50"></i>
                <p class="mb-0">Belum ada yang merepost.</p>
            </div>
        @endforelse
    @else
        @forelse($likers as $user)
            @include('components.user-row', ['user' => $user, 'authUser' => $authUser])
        @empty
            <div class="text-center py-5 text-secondary">
                <i class="ri-heart-line d-block fs-1 mb-3 opacity-50"></i>
                <p class="mb-0">Belum ada yang menyukai.</p>
            </div>
        @endforelse
    @endif
</div>

</x-layouts.app>
