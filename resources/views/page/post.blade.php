<x-layouts.app title="Post — Tenebris">
    {{-- Header --}}
    <div class="feed-header d-flex align-items-center gap-3">
        <a href="{{ route('home') }}" class="post-action-btn">
            <i class="ri-arrow-left-line fs-5"></i>
        </a>
        <span>Post</span>
    </div>

    @php
        $authUser = auth()->user();
        $firstPageReplies = $post->replies()
            ->with(['postMedia', 'user.profile'])
            ->latest()
            ->paginate(15);
    @endphp

    {{-- Main post --}}
    <div class="post-card" style="cursor:default">
        <div class="d-flex gap-3">
            <div>
                @if($post->user->profile?->avatar)
                    <img src="{{ asset('storage/' . $post->user->profile->avatar) }}"
                         alt="{{ $post->user->name }}" class="post-avatar">
                @else
                    <div class="post-avatar-placeholder">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-start">
                    <div>
                        <div class="post-username">{{ $post->user->name }}</div>
                        <div class="post-handle">{{ '@' . $post->user->name }}</div>
                    </div>
                    @if($authUser->id === $post->user_id)
                        <div class="ms-auto">
                            <form method="POST" action="{{ route('post.destroy', $post) }}"
                                  id="del-post-{{ $post->id }}"
                                  onclick="event.stopPropagation()">
                                @csrf @method('DELETE')
                                <button type="button" class="post-action-btn"
                                        onclick="event.stopPropagation(); confirmDelete('del-post-{{ $post->id }}')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                @if($post->content)
                    <p class="mt-2 mb-0" style="font-size:1.15rem;line-height:1.55">{{ $post->content }}</p>
                @endif

                @if($post->postMedia->count() > 0)
                    @php $detailUrls = $post->postMedia->map(fn($m) => asset('storage/'.$m->image))->values()->toArray(); @endphp
                    <div class="post-media-grid grid-{{ $post->postMedia->count() }} mt-2">
                        @foreach($post->postMedia as $idx => $media)
                            <img src="{{ asset('storage/'.$media->image) }}" alt="media" loading="lazy"
                                 class="post-media-thumb"
                                 onclick="openLightbox({{ json_encode($detailUrls) }}, {{ $idx }})"
                                 style="cursor:zoom-in">
                        @endforeach
                    </div>
                @endif

                <div class="post-time mt-2" style="font-size:.88rem">
                    {{ $post->created_at->format('H:i · d M Y') }}
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-4 mt-3 pt-3" style="border-top:1px solid #e1e8ed">
            <span style="font-size:.92rem;color:#536471">
                <strong style="color:#0f1419">{{ $post->reposts_count }}</strong> Repost
            </span>
            <span style="font-size:.92rem;color:#536471">
                <strong style="color:#0f1419">{{ $post->likes_count }}</strong> Suka
            </span>
        </div>

        <div class="post-actions d-flex gap-1 pt-2 mt-1" style="border-top:1px solid #e1e8ed">
            <button class="post-action-btn" data-bs-toggle="collapse" data-bs-target="#replyInline">
                <i class="ri-chat-1-line"></i>
            </button>
            <form method="POST" action="{{ route('post.repost', $post) }}" class="d-inline">
                @csrf
                @php $isReposted = $authUser->posts()->where('repost_of_id', $post->id)->exists(); @endphp
                <button type="submit" class="post-action-btn {{ $isReposted ? 'reposted' : '' }}">
                    <i class="ri-repeat-line"></i>
                </button>
            </form>
            <form method="POST" action="{{ route('post.like', $post) }}" class="d-inline">
                @csrf
                @php $isLiked = $post->isLiked($authUser); @endphp
                <button type="submit" class="post-action-btn {{ $isLiked ? 'liked' : '' }}">
                    <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Inline reply box --}}
    <div class="collapse" id="replyInline">
        <div class="compose-box">
            <form method="POST" action="{{ route('post.reply', $post) }}" enctype="multipart/form-data">
                @csrf
                <div class="d-flex gap-2">
                    <div class="post-avatar-placeholder" style="width:38px;height:38px;font-size:.9rem">
                        {{ strtoupper(substr($authUser->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <textarea name="content" class="compose-textarea"
                                  placeholder="Tulis balasanmu..." maxlength="256" required rows="2"></textarea>
                        <div id="postReplyMediaPreview" class="compose-media-preview d-none"></div>
                        <div class="d-flex align-items-center justify-content-between pt-1"
                             style="border-top:1px solid var(--border)">
                            <label style="cursor:pointer;color:#1d9bf0" title="Tambah gambar">
                                <i class="ri-image-line fs-5"></i>
                                <input type="file" name="media[]" accept="image/*" multiple class="d-none"
                                       onchange="previewImages(this, 'postReplyMediaPreview')">
                            </label>
                            <button type="submit" class="btn btn-sm rounded-pill fw-bold px-3"
                                    style="background:#1d9bf0;color:#fff;font-size:.85rem">Balas</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Replies --}}
    <div id="replies-container">
        @forelse($firstPageReplies as $reply)
            <x-post-card :post="$reply" :authUser="$authUser" />
        @empty
            <div class="text-center py-4" style="color:#8b98a5">
                <p class="mb-0">Belum ada balasan. Jadilah yang pertama!</p>
            </div>
        @endforelse
    </div>

    {{-- Tombol muat lebih banyak balasan --}}
    @if($firstPageReplies->hasMorePages())
    <div class="load-more-wrap" id="wrap-replies">
        <button class="btn-load-more"
                data-next="2"
                data-url="{{ route('post.load-more-replies', $post) }}"
                data-container="replies-container"
                data-wrap="wrap-replies"
                onclick="loadMoreReplies(this)">
            <i class="ri-refresh-line me-1"></i> Muat Lebih Banyak
        </button>
    </div>
    @endif

</x-layouts.app>
