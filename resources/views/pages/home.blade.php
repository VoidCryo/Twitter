<x-layouts.app title="Beranda · Tenebris">

<div class="mx-auto" style="max-width:600px;">
    {{-- Tabs --}}
    <div class="d-flex border-bottom sticky-top bg-white" style="top:52px;z-index:89;">
        <a href="{{ route('home', ['tab' => 'for-you']) }}"
           class="flex-fill text-center py-3 fw-medium text-decoration-none position-relative {{ $tab !== 'following' ? 'text-dark fw-bold' : 'text-secondary' }}">
            Untuk Kamu
            @if($tab !== 'following')<span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-pill" style="height:3px;width:40px;"></span>@endif
        </a>
        <a href="{{ route('home', ['tab' => 'following']) }}"
           class="flex-fill text-center py-3 fw-medium text-decoration-none position-relative {{ $tab === 'following' ? 'text-dark fw-bold' : 'text-secondary' }}">
            Mengikuti
            @if($tab === 'following')<span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-pill" style="height:3px;width:40px;"></span>@endif
        </a>
    </div>

    {{-- Feed --}}
    @forelse($posts as $post)
        <x-post-card :post="$post" :authUser="$user" />
    @empty
        <div class="text-center py-5 text-secondary">
            <i class="ri-quill-pen-line d-block fs-1 mb-3 opacity-50"></i>
            <p class="mb-0">
                @if($tab === 'following')
                    Belum ada post dari orang yang kamu ikuti.
                @else
                    Belum ada post. Jadilah yang pertama!
                @endif
            </p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($posts->hasPages())
    <div class="d-flex justify-content-center gap-2 p-3">
        @if($posts->onFirstPage())
            <span class="btn btn-outline-secondary btn-sm rounded-pill disabled">‹ Sebelumnya</span>
        @else
            <a href="{{ $posts->previousPageUrl() }}" class="btn btn-outline-secondary btn-sm rounded-pill">‹ Sebelumnya</a>
        @endif
        @if($posts->hasMorePages())
            <a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline-secondary btn-sm rounded-pill">Selanjutnya ›</a>
        @else
            <span class="btn btn-outline-secondary btn-sm rounded-pill disabled">Selanjutnya ›</span>
        @endif
    </div>
    @endif
</div>

</x-layouts.app>
