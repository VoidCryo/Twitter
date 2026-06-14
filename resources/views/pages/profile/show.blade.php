<x-layouts.app :title="($user->profile?->display_name ?? $user->name) . ' · Tenebris'">

<div class="mx-auto" style="max-width:600px;">
    {{-- Back header --}}
    <div class="sticky-top bg-white border-bottom d-flex align-items-center gap-3 px-3 py-2" style="top:52px;z-index:89;min-height:52px;">
        <a href="{{ route('home') }}" class="text-dark fs-5 text-decoration-none">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <div class="fw-bold" style="font-size:18px;">{{ $user->profile?->display_name ?? $user->name }}</div>
            <div class="text-secondary small">{{ $user->posts_count }} post</div>
        </div>
    </div>

    {{-- Banner --}}
    @if($user->profile?->banner)
        <img src="{{ Storage::url($user->profile->banner) }}" class="w-100 d-block object-fit-cover" style="height:130px;" alt="banner">
    @else
        <div class="w-100" style="height:130px;background:linear-gradient(135deg,#1d9bf0,#0f59a4);"></div>
    @endif

    {{-- Avatar + Actions row --}}
    <div class="d-flex justify-content-between align-items-end px-3" style="margin-top:-38px;">
        @if($user->profile?->avatar)
            <img src="{{ Storage::url($user->profile->avatar) }}" class="rounded-circle border border-3 border-white object-fit-cover" style="width:76px;height:76px;" alt="avatar">
        @else
            <div class="rounded-circle border border-3 border-white bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-secondary" style="width:76px;height:76px;font-size:28px;">{{ strtoupper(substr($user->profile?->display_name ?? $user->name, 0, 1)) }}</div>
        @endif

        <div class="mb-1 d-flex gap-2">
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-semibold">Edit Profil</a>
            @else
                <form action="{{ route('follow.toggle', $user) }}" method="POST">
                    @csrf
                    @if($authUser->isFollowing($user))
                        <button type="submit" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-semibold">Mengikuti</button>
                    @else
                        <button type="submit" class="btn btn-primary rounded-pill btn-sm px-3 fw-semibold">Ikuti</button>
                    @endif
                </form>
            @endif
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="px-3 pt-2">
        <div class="fw-bold" style="font-size:20px;">{{ $user->profile?->display_name ?? $user->name }}</div>
        <div class="text-secondary" style="font-size:15px;">{{ '@' . $user->name }}</div>

        @if($user->profile?->bio)
            <p class="mt-2 mb-0" style="font-size:15px;line-height:1.5;">{{ $user->profile->bio }}</p>
        @endif

        <div class="d-flex gap-3 flex-wrap mt-2">
            @if($user->profile?->location)
                <div class="d-flex align-items-center gap-1 text-secondary small">
                    <i class="ri-map-pin-line"></i>
                    {{ $user->profile->location }}
                </div>
            @endif
            @if($user->profile?->birthday)
                <div class="d-flex align-items-center gap-1 text-secondary small">
                    <i class="ri-cake-2-line"></i>
                    Lahir {{ \Carbon\Carbon::parse($user->profile->birthday)->translatedFormat('d F Y') }}
                </div>
            @endif
            <div class="d-flex align-items-center gap-1 text-secondary small">
                <i class="ri-calendar-line"></i>
                Bergabung {{ $user->created_at->translatedFormat('F Y') }}
            </div>
        </div>

        <div class="d-flex gap-4 mt-3 pb-3 border-bottom" style="font-size:15px;">
            <a href="{{ route('profile.following', $user) }}" class="text-dark text-decoration-none">
                <strong>{{ $user->followings_count }}</strong>
                <span class="text-secondary"> Mengikuti</span>
            </a>
            <a href="{{ route('profile.followers', $user) }}" class="text-dark text-decoration-none">
                <strong>{{ $user->followers_count }}</strong>
                <span class="text-secondary"> Pengikut</span>
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="d-flex border-bottom sticky-top bg-white" style="top:52px;z-index:88;">
        <a href="{{ route('profile', ['user' => $user, 'tab' => 'posts']) }}"
           class="flex-fill text-center py-3 text-decoration-none position-relative {{ $tab !== 'likes' ? 'fw-bold text-dark' : 'fw-medium text-secondary' }}">
            Post
            @if($tab !== 'likes')<span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-pill" style="height:3px;width:40px;"></span>@endif
        </a>
        <a href="{{ route('profile', ['user' => $user, 'tab' => 'likes']) }}"
           class="flex-fill text-center py-3 text-decoration-none position-relative {{ $tab === 'likes' ? 'fw-bold text-dark' : 'fw-medium text-secondary' }}">
            Suka
            @if($tab === 'likes')<span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-pill" style="height:3px;width:40px;"></span>@endif
        </a>
    </div>

    {{-- Posts / Liked --}}
    @php $feedPosts = $tab === 'likes' ? $likedPosts : $posts; @endphp
    @forelse($feedPosts as $post)
        <x-post-card :post="$post" :authUser="$authUser" />
    @empty
        <div class="text-center py-5 text-secondary">
            <i class="{{ $tab === 'likes' ? 'ri-heart-line' : 'ri-quill-pen-line' }} d-block fs-1 mb-3 opacity-50"></i>
            <p class="mb-0">{{ $tab === 'likes' ? 'Belum ada post yang disukai.' : 'Belum ada post.' }}</p>
        </div>
    @endforelse
</div>

</x-layouts.app>
