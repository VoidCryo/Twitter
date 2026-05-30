@props(['post', 'authUser'])

<article class="post-card">
    {{-- Repost label --}}
    @if($post->repost_of_id && $post->repost_of)
        <div class="repost-label d-flex align-items-center gap-1 mb-2 ms-5 ps-2">
            <i class="ri-repeat-line"></i>
            <span>{{ $post->user->name }} merepost</span>
        </div>
        @php $displayPost = $post->repost_of; @endphp
    @else
        @php $displayPost = $post; @endphp
    @endif

    {{-- Entire card is clickable → post detail page --}}
    <a href="{{ route('post', $displayPost) }}" class="post-card-link" aria-label="Lihat post"></a>

    <div class="d-flex gap-3">
        {{-- Avatar --}}
        <div>
            @if($displayPost->user->profile?->avatar)
                <img src="{{ asset('storage/' . $displayPost->user->profile->avatar) }}"
                     alt="{{ $displayPost->user->name }}"
                     class="post-avatar">
            @else
                <div class="post-avatar-placeholder">
                    {{ strtoupper(substr($displayPost->user->name, 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="flex-grow-1 min-w-0">
            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="post-username">{{ $displayPost->user->name }}</span>
                <span class="post-handle">{{ '@' . $displayPost->user->name }}</span>
                <span class="post-time">· {{ $displayPost->created_at->diffForHumans() }}</span>

                <div class="ms-auto">
                    @if($authUser->id === $displayPost->user_id)
                        {{-- Tombol hapus untuk post sendiri --}}
                        <form method="POST" action="{{ route('post.destroy', $displayPost) }}"
                              class="d-inline" id="del-{{ $displayPost->id }}"
                              onclick="event.stopPropagation()">
                            @csrf @method('DELETE')
                            <button type="button" class="post-action-btn"
                                    onclick="event.stopPropagation(); confirmDelete('del-{{ $displayPost->id }}')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    @else
                        {{-- Tombol follow untuk post orang lain --}}
                        <form method="POST" action="{{ route('user.follow', $displayPost->user) }}"
                              class="d-inline" onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="btn-follow" style="font-size:.78rem;padding:.2rem .75rem">
                                {{ $authUser->isFollowing($displayPost->user) ? 'Unfollow' : 'Follow' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Body --}}
            @if($displayPost->content)
                <p class="post-content mb-0">{{ $displayPost->content }}</p>
            @endif

            {{-- Media — clickable lightbox --}}
            @if($displayPost->postMedia->count() > 0)
                @php
                    $mediaId = 'media-' . $displayPost->id;
                    $mediaUrls = $displayPost->postMedia->map(fn($m) => asset('storage/' . $m->image))->values()->toArray();
                @endphp
                <div class="post-media-grid grid-{{ $displayPost->postMedia->count() }}"
                     onclick="event.stopPropagation()">
                    @foreach($displayPost->postMedia as $index => $media)
                        <img src="{{ asset('storage/' . $media->image) }}"
                             alt="media" loading="lazy"
                             class="post-media-thumb"
                             onclick="openLightbox({{ json_encode($mediaUrls) }}, {{ $index }})"
                             style="cursor:zoom-in">
                    @endforeach
                </div>
            @endif

            {{-- Actions --}}
            <div class="post-actions d-flex gap-1 mt-2" onclick="event.stopPropagation()">
                {{-- Reply --}}
                <button class="post-action-btn" data-bs-toggle="modal"
                        data-bs-target="#replyModal{{ $displayPost->id }}">
                    <i class="ri-chat-1-line"></i>
                    <span>{{ $displayPost->replies_count }}</span>
                </button>

                {{-- Repost --}}
                <form method="POST" action="{{ route('post.repost', $displayPost) }}" class="d-inline">
                    @csrf
                    @php $isReposted = $authUser->posts()->where('repost_of_id', $displayPost->id)->exists(); @endphp
                    <button type="submit" class="post-action-btn {{ $isReposted ? 'reposted' : '' }}">
                        <i class="ri-repeat-line"></i>
                        <span>{{ $displayPost->reposts_count }}</span>
                    </button>
                </form>

                {{-- Like --}}
                <form method="POST" action="{{ route('post.like', $displayPost) }}" class="d-inline">
                    @csrf
                    @php $isLiked = $displayPost->isLiked($authUser); @endphp
                    <button type="submit" class="post-action-btn {{ $isLiked ? 'liked' : '' }}">
                        <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                        <span>{{ $displayPost->likes_count }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</article>

{{-- Reply Modal --}}
<div class="modal fade" id="replyModal{{ $displayPost->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Balas Post</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-1">
                <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid #e1e8ed">
                    <div class="post-avatar-placeholder" style="width:36px;height:36px;font-size:.85rem">
                        {{ strtoupper(substr($displayPost->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="post-username" style="font-size:.875rem">{{ $displayPost->user->name }}</span>
                        <p class="mb-0 mt-1" style="font-size:.9rem;color:#536471">{{ Str::limit($displayPost->content, 100) }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('post.reply', $displayPost) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex gap-3">
                        <div class="post-avatar-placeholder" style="width:36px;height:36px;font-size:.85rem">
                            {{ strtoupper(substr($authUser->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <textarea name="content" class="compose-textarea"
                                      placeholder="Tulis balasan..." maxlength="256" required rows="3"
                                      style="font-size:.95rem"></textarea>
                            <div id="replyPreview{{ $displayPost->id }}" class="compose-media-preview d-none"></div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <label class="text-secondary" style="cursor:pointer" title="Tambah media">
                                    <i class="ri-image-line fs-5" style="color:#1d9bf0"></i>
                                    <input type="file" name="media[]" accept="image/*" multiple class="d-none"
                                           onchange="previewImages(this, 'replyPreview{{ $displayPost->id }}')">
                                </label>
                                <button type="submit" class="btn rounded-pill fw-bold px-4"
                                        style="background:#1d9bf0;color:#fff;font-size:.9rem">Balas</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
