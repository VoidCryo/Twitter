<x-layouts.app title="Cari · Tenebris">

<div class="container-lg">
    <div class="row justify-content-center">

        {{-- KOLOM UTAMA --}}
        <div class="col-12 col-md-8 col-lg-7" style="max-width: 600px;">
            <div class="sticky-top bg-white border-bottom px-3 py-2" style="top:52px;z-index:89;">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 rounded-start-pill">
                            <i class="ri-search-line text-secondary"></i>
                        </span>
                        <input type="text" name="query" value="{{ $key ?? '' }}"
                               placeholder="Cari di Tenebris"
                               class="form-control bg-light border-0 rounded-end-pill shadow-none"
                               autofocus>
                    </div>
                </form>
            </div>

            @if(isset($key) && $key)
                {{-- Users --}}
                @if($user && $user->count() > 0)
                    <div class="px-3 pt-3 pb-1 fw-bold fs-5">Orang</div>
                    @foreach($user as $u)
                        @include('components.user-row', ['user' => $u, 'authUser' => auth()->user()])
                    @endforeach
                @endif

                {{-- Posts --}}
                @if($post && $post->count() > 0)
                    <div class="px-3 pt-3 pb-1 fw-bold fs-5">Post</div>
                    @foreach($post as $p)
                        <x-post-card :post="$p" :authUser="auth()->user()" />
                    @endforeach
                @endif

                @if((!$user || $user->count() === 0) && (!$post || $post->count() === 0))
                    <div class="text-center py-5 text-secondary">
                        <i class="ri-search-line d-block fs-1 mb-3 opacity-50"></i>
                        <p class="mb-0">Tidak ada hasil untuk "{{ $key }}"</p>
                    </div>
                @endif
            @else
                <div class="text-center py-5 text-secondary" style="padding-top:60px!important;">
                    <i class="ri-search-2-line d-block fs-1 mb-3 opacity-50"></i>
                    <p class="mb-0">Cari orang atau post</p>
                </div>
            @endif
        </div>

        {{-- KOLOM SIDEBAR KANAN --}}
        @if(isset($newUsers) && $newUsers->count() > 0)
        <div class="col-lg-4 d-none d-lg-block">
            <div style="position: sticky; top: 68px;">
                <div class="bg-light rounded-4 overflow-hidden">
                    <div class="px-3 pt-3 pb-2 fw-bold fs-6">Saran Untukmu</div>
                    @foreach($newUsers as $u)
                        @include('components.user-row', ['user' => $u, 'authUser' => auth()->user()])
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

</x-layouts.app>
