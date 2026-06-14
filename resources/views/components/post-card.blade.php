@php
    $displayPost = $post->repost_of ?? $post;
    $isRepost    = !is_null($post->repost_of_id);
    $isLiked     = $authUser ? $displayPost->isLiked($authUser) : false;
    $isReposted  = $authUser
        ? \App\Models\Post::where('user_id', $authUser->id)->where('repost_of_id', $displayPost->id)->exists()
        : false;
    $isOwner     = $authUser && $post->user_id === $authUser->id;
    $mediaUrls   = $displayPost->postMedia->map(fn($m) => Storage::url($m->image))->values()->toArray();
    $mediaCnt    = count($mediaUrls);
@endphp

<article class="border-bottom px-3 py-2">
    {{-- Repost label --}}
    @if($isRepost)
    <div class="d-flex align-items-center gap-2 text-secondary small mb-2 ps-5">
        <i class="ri-repeat-2-line"></i>
        <a href="{{ route('profile', $post->user) }}" class="text-secondary fw-semibold text-decoration-none">{{ $post->user->profile?->display_name ?? $post->user->name }}</a>
        merepost
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-start">
        <div class="d-flex gap-2 flex-grow-1 min-w-0">
            @php $profile = $displayPost->user->profile; @endphp
            <a href="{{ route('profile', $displayPost->user) }}" class="flex-shrink-0">
                @if($profile?->avatar)
                    <img src="{{ Storage::url($profile->avatar) }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
                @else
                    <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-secondary flex-shrink-0" style="width:40px;height:40px;">{{ strtoupper(substr($displayPost->user->profile?->display_name ?? $displayPost->user->name, 0, 1)) }}</div>
                @endif
            </a>
            <div class="d-flex align-items-center flex-wrap gap-1" style="line-height:1.3;">
                <a href="{{ route('profile', $displayPost->user) }}" class="fw-bold text-dark text-decoration-none" style="font-size:15px;">{{ $displayPost->user->profile?->display_name ?? $displayPost->user->name }}</a>
                <span class="text-secondary small">{{ '@' . $displayPost->user->name }}</span>
                <span class="text-secondary small">· {{ $displayPost->created_at->diffForHumans(null, true, true) }}</span>
            </div>
        </div>

        <div class="ms-2">
            @if($isOwner)
            <form action="{{ route('post.destroy', $post) }}" method="POST" id="deleteForm{{ $post->id }}">
                @csrf @method('DELETE')
                <button type="button" class="btn btn-link text-secondary p-1" onclick="confirmDelete('deleteForm{{ $post->id }}')">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </form>
            @elseif($authUser && !$isRepost && $displayPost->user_id !== $authUser->id)
            <form action="{{ route('follow.toggle', $displayPost->user) }}" method="POST">
                @csrf
                @if($authUser->isFollowing($displayPost->user))
                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">Mengikuti</button>
                @else
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">Ikuti</button>
                @endif
            </form>
            @endif
        </div>
    </div>

    {{-- Content --}}
    @if($displayPost->content)
        <a href="{{ route('post', $displayPost) }}" class="d-block text-dark text-decoration-none ms-5 mt-1 mb-2" style="font-size:15px;line-height:1.5;white-space:pre-wrap;word-break:break-word;">{{ $displayPost->content }}</a>
    @endif

    {{-- Media Grid --}}
    @if($mediaCnt > 0)
    <div class="ms-5 mb-2 tnb-media-grid tnb-media-{{ $mediaCnt }}" style="border-radius:12px;overflow:hidden;">
        @if($mediaCnt === 1)
            <img src="{{ $mediaUrls[0] }}" alt="media" loading="lazy"
                 style="width:100%;height:280px;object-fit:cover;display:block;cursor:zoom-in;"
                 onclick="event.stopPropagation();openLightbox({{ json_encode($mediaUrls) }},0)">

        @elseif($mediaCnt === 2)
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:3px;">
                @foreach($mediaUrls as $gi => $url)
                <img src="{{ $url }}" alt="media" loading="lazy"
                     style="width:100%;height:200px;object-fit:cover;display:block;cursor:zoom-in;"
                     onclick="event.stopPropagation();openLightbox({{ json_encode($mediaUrls) }},{{ $gi }})">
                @endforeach
            </div>

        @elseif($mediaCnt === 3)
            <div style="display:flex;gap:3px;height:280px;">
                <div style="flex:2;">
                    <img src="{{ $mediaUrls[0] }}" alt="media" loading="lazy"
                         style="width:100%;height:100%;object-fit:cover;display:block;cursor:zoom-in;"
                         onclick="event.stopPropagation();openLightbox({{ json_encode($mediaUrls) }},0)">
                </div>
                <div style="flex:1;display:flex;flex-direction:column;gap:3px;">
                    <img src="{{ $mediaUrls[1] }}" alt="media" loading="lazy"
                         style="width:100%;flex:1;object-fit:cover;display:block;cursor:zoom-in;min-height:0;"
                         onclick="event.stopPropagation();openLightbox({{ json_encode($mediaUrls) }},1)">
                    <img src="{{ $mediaUrls[2] }}" alt="media" loading="lazy"
                         style="width:100%;flex:1;object-fit:cover;display:block;cursor:zoom-in;min-height:0;"
                         onclick="event.stopPropagation();openLightbox({{ json_encode($mediaUrls) }},2)">
                </div>
            </div>

        @else
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:3px;">
                @foreach(array_slice($mediaUrls,0,4) as $gi => $url)
                <img src="{{ $url }}" alt="media" loading="lazy"
                     style="width:100%;height:180px;object-fit:cover;display:block;cursor:zoom-in;"
                     onclick="event.stopPropagation();openLightbox({{ json_encode($mediaUrls) }},{{ $gi }})">
                @endforeach
            </div>
        @endif
    </div>
    @endif

    {{-- Actions --}}
    <div class="d-flex ps-5 mt-1">
        <a href="{{ route('post', $displayPost) }}" class="btn btn-link text-secondary d-flex align-items-center gap-1 flex-fill justify-content-center px-2 py-1 text-decoration-none">
            <i class="ri-chat-1-line fs-5"></i>
            @if($displayPost->replies_count > 0)<span class="small fw-medium">{{ $displayPost->replies_count }}</span>@endif
        </a>
        <form action="{{ route('post.repost', $displayPost) }}" method="POST" class="flex-fill">
            @csrf
            <button type="submit" class="btn btn-link d-flex align-items-center gap-1 w-100 justify-content-center px-2 py-1 {{ $isReposted ? 'text-success' : 'text-secondary' }} text-decoration-none">
                <i class="ri-repeat-2-line fs-5"></i>
                @if($displayPost->reposts_count > 0)<span class="small fw-medium">{{ $displayPost->reposts_count }}</span>@endif
            </button>
        </form>
        <form action="{{ route('post.like', $displayPost) }}" method="POST" class="flex-fill">
            @csrf
            <button type="submit" class="btn btn-link d-flex align-items-center gap-1 w-100 justify-content-center px-2 py-1 {{ $isLiked ? 'text-danger' : 'text-secondary' }} text-decoration-none">
                <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }} fs-5"></i>
                @if($displayPost->liked_by_count > 0)<span class="small fw-medium">{{ $displayPost->liked_by_count }}</span>@endif
            </button>
        </form>
        <a href="{{ route('post.interactions', $displayPost) }}" class="btn btn-link text-secondary d-flex align-items-center gap-1 flex-fill justify-content-center px-2 py-1 text-decoration-none">
            <i class="ri-bar-chart-line fs-5"></i>
        </a>
    </div>
</article>
