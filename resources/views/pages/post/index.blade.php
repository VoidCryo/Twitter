<x-layouts.app :title="'Post · Tenebris'">

<div class="mx-auto" style="max-width:600px;">
    {{-- Header --}}
    <div class="sticky-top bg-white border-bottom d-flex align-items-center gap-3 px-3 py-2" style="top:52px;z-index:89;min-height:52px;">
        <a href="{{ url()->previous() }}" class="text-dark fs-5 text-decoration-none">
            <i class="ri-arrow-left-line"></i>
        </a>
        <span class="fw-bold fs-5">Post</span>
    </div>

    {{-- Main Post --}}
    <div class="px-3 pt-3">
        @php
            $profile   = $post->user->profile;
            $mediaUrls = $post->postMedia->map(fn($m) => Storage::url($m->image))->values()->toArray();
            $mediaCnt  = count($mediaUrls);
        @endphp

        {{-- Author row --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('profile', $post->user) }}">
                    @if($profile?->avatar)
                        <img src="{{ Storage::url($profile->avatar) }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
                    @else
                        <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-secondary" style="width:40px;height:40px;">{{ strtoupper(substr($post->user->profile?->display_name ?? $post->user->name, 0, 1)) }}</div>
                    @endif
                </a>
                <div>
                    <a href="{{ route('profile', $post->user) }}" class="fw-bold text-dark text-decoration-none d-block fs-6">{{ $post->user->profile?->display_name ?? $post->user->name }}</a>
                    <div class="text-secondary small">{{ '@' . $post->user->name }}</div>
                </div>
            </div>
            @if($authUser && $authUser->id === $post->user_id)
            <form action="{{ route('post.destroy', $post) }}" method="POST" id="deleteForm{{ $post->id }}">
                @csrf @method('DELETE')
                <button type="button" class="btn btn-link text-secondary p-2 fs-5" onclick="confirmDelete('deleteForm{{ $post->id }}')" title="Hapus">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </form>
            @endif
        </div>

        {{-- Content --}}
        @if($post->content)
            <p class="fs-5 lh-base mb-3">{{ $post->content }}</p>
        @endif

        {{-- Error Messages --}}
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                <i class="ri-error-warning-line"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                <i class="ri-error-warning-line"></i>
                <div class="flex-grow-1">{{ $errors->first() }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Media --}}
        @if($mediaCnt > 0)
        <div class="rounded-3 overflow-hidden mb-3">
            @if($mediaCnt === 1)
                <img src="{{ $mediaUrls[0] }}" alt="media" loading="lazy"
                     style="width:100%;height:320px;object-fit:cover;display:block;cursor:zoom-in;"
                     onclick="openLightbox({{ json_encode($mediaUrls) }},0)">

            @elseif($mediaCnt === 2)
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:3px;">
                    @foreach($mediaUrls as $gi => $url)
                    <img src="{{ $url }}" alt="media" loading="lazy"
                         style="width:100%;height:220px;object-fit:cover;display:block;cursor:zoom-in;"
                         onclick="openLightbox({{ json_encode($mediaUrls) }},{{ $gi }})">
                    @endforeach
                </div>

            @elseif($mediaCnt === 3)
                {{-- Left big + right 2 stacked --}}
                <div style="display:flex;gap:3px;height:320px;">
                    <div style="flex:2;">
                        <img src="{{ $mediaUrls[0] }}" alt="media" loading="lazy"
                             style="width:100%;height:100%;object-fit:cover;display:block;cursor:zoom-in;"
                             onclick="openLightbox({{ json_encode($mediaUrls) }},0)">
                    </div>
                    <div style="flex:1;display:flex;flex-direction:column;gap:3px;">
                        <img src="{{ $mediaUrls[1] }}" alt="media" loading="lazy"
                             style="width:100%;flex:1;object-fit:cover;display:block;cursor:zoom-in;min-height:0;"
                             onclick="openLightbox({{ json_encode($mediaUrls) }},1)">
                        <img src="{{ $mediaUrls[2] }}" alt="media" loading="lazy"
                             style="width:100%;flex:1;object-fit:cover;display:block;cursor:zoom-in;min-height:0;"
                             onclick="openLightbox({{ json_encode($mediaUrls) }},2)">
                    </div>
                </div>

            @else
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:3px;">
                    @foreach(array_slice($mediaUrls,0,4) as $gi => $url)
                    <img src="{{ $url }}" alt="media" loading="lazy"
                         style="width:100%;height:200px;object-fit:cover;display:block;cursor:zoom-in;"
                         onclick="openLightbox({{ json_encode($mediaUrls) }},{{ $gi }})">
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        {{-- Timestamp --}}
        <div class="text-secondary small pb-3 border-bottom">
            {{ $post->created_at->format('H:i') }} · {{ $post->created_at->translatedFormat('d F Y') }}
        </div>

        {{-- Stats --}}
        @php
            $isLiked    = $authUser ? $post->isLiked($authUser) : false;
            $isReposted = $authUser
                ? \App\Models\Post::where('user_id', $authUser->id)->where('repost_of_id', $post->id)->exists()
                : false;
        @endphp
        <div class="d-flex gap-4 py-3 border-bottom fs-6">
            <a href="#replyForm" class="text-dark text-decoration-none">
                <strong>{{ $post->replies_count ?? 0 }}</strong>
                <span class="text-secondary"> Balasan</span>
            </a>
            <a href="{{ route('post.interactions', $post) }}" class="text-dark text-decoration-none">
                <strong>{{ $post->reposts_count ?? 0 }}</strong>
                <span class="text-secondary"> Repost</span>
            </a>
            <a href="{{ route('post.interactions', $post) }}" class="text-dark text-decoration-none">
                <strong>{{ $post->liked_by_count ?? 0 }}</strong>
                <span class="text-secondary"> Suka</span>
            </a>
        </div>

        {{-- Post Actions --}}
        <div class="d-flex border-bottom">
            <a href="#replyForm" class="btn btn-link text-secondary d-flex align-items-center gap-1 flex-fill justify-content-center py-2">
                <i class="ri-chat-1-line fs-5"></i>
            </a>
            <form action="{{ route('post.repost', $post) }}" method="POST" class="flex-fill">
                @csrf
                <button type="submit" class="btn btn-link d-flex align-items-center gap-1 w-100 justify-content-center py-2 {{ $isReposted ? 'text-success' : 'text-secondary' }}">
                    <i class="ri-repeat-2-line fs-5"></i>
                </button>
            </form>
            <form action="{{ route('post.like', $post) }}" method="POST" class="flex-fill">
                @csrf
                <button type="submit" class="btn btn-link d-flex align-items-center gap-1 w-100 justify-content-center py-2 {{ $isLiked ? 'text-danger' : 'text-secondary' }}">
                    <i class="{{ $isLiked ? 'ri-heart-fill' : 'ri-heart-line' }} fs-5"></i>
                </button>
            </form>
            <a href="{{ route('post.interactions', $post) }}" class="btn btn-link text-secondary d-flex align-items-center gap-1 flex-fill justify-content-center py-2">
                <i class="ri-bar-chart-line fs-5"></i>
            </a>
        </div>
    </div>

    {{-- Reply Form (hanya untuk user yang login) --}}
    @auth
    <div id="replyForm" class="px-3 py-3 border-bottom">
        <form action="{{ route('post.reply', $post) }}" method="POST" enctype="multipart/form-data" id="replyFormEl">
            @csrf
            <div class="d-flex gap-3">
                @php $authProfile = $authUser?->profile; @endphp
                @if($authProfile?->avatar)
                    <img src="{{ Storage::url($authProfile->avatar) }}" class="rounded-circle flex-shrink-0" style="width:40px;height:40px;object-fit:cover;" alt="">
                @else
                    <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-secondary flex-shrink-0" style="width:40px;height:40px;">{{ strtoupper(substr($authUser->name, 0, 1)) }}</div>
                @endif
                <div class="flex-grow-1">
                    <textarea name="content" id="replyContent" class="form-control border-0 p-0 shadow-none bg-transparent"
                              placeholder="Tulis balasan..."
                              style="resize:none;min-height:56px;font-size:16px;"
                              maxlength="280"></textarea>

                    {{-- Toolbar row --}}
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <div class="d-flex align-items-center gap-2">
                            <label for="replyMedia" class="text-primary mb-0" style="cursor:pointer;">
                                <i class="ri-image-line fs-5"></i>
                                <input type="file" name="media[]" id="replyMedia" class="d-none" multiple accept="image/*">
                            </label>
                            <span id="replyMediaCount" class="text-secondary small"></span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span id="replyCharCount" class="text-secondary small">0/280</span>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold" id="replySubmitBtn" disabled>Balas</button>
                        </div>
                    </div>

                    {{-- Preview thumbnails --}}
                    <div id="replyMediaPreview" class="d-flex gap-2 flex-wrap mt-2"></div>
                </div>
            </div>
        </form>
    </div>
    @endauth

    {{-- Replies --}}
    @forelse($replies as $reply)
        <x-post-card :post="$reply" :authUser="$authUser" />
    @empty
        <div class="text-center py-5 text-secondary">
            <i class="ri-chat-1-line d-block fs-1 mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada balasan. Jadilah yang pertama!</p>
        </div>
    @endforelse

    @if($replies->hasPages())
    <div class="text-center p-3">
        {{ $replies->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
(function () {
    var MAX = 4;
    var ta  = document.getElementById('replyContent');
    var cc  = document.getElementById('replyCharCount');
    var sb  = document.getElementById('replySubmitBtn');
    var mi  = document.getElementById('replyMedia');
    var mp  = document.getElementById('replyMediaPreview');
    var mc  = document.getElementById('replyMediaCount');
    var selectedFiles = [];

    function syncInput() {
        var dt = new DataTransfer();
        selectedFiles.forEach(function (f) { dt.items.add(f); });
        mi.files = dt.files;
    }

    function updateSubmit() {
        var hasText  = ta && ta.value.trim().length > 0;
        var hasMedia = selectedFiles.length > 0;
        if (sb) sb.disabled = !(hasText || hasMedia);
    }

    function updateCounter() {
        mc.textContent = selectedFiles.length > 0 ? selectedFiles.length + '/' + MAX : '';
        mc.className   = selectedFiles.length >= MAX ? 'text-danger small fw-semibold' : 'text-secondary small';
    }

    function renderPreviews() {
        mp.innerHTML = '';
        selectedFiles.forEach(function (f, i) {
            var wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;display:inline-block;';
            var img  = document.createElement('img');
            img.src  = URL.createObjectURL(f);
            img.style.cssText = 'width:64px;height:64px;object-fit:cover;border-radius:8px;display:block;';
            var btn  = document.createElement('button');
            btn.type = 'button';
            btn.style.cssText = 'position:absolute;top:-5px;right:-5px;width:20px;height:20px;border-radius:50%;background:rgba(0,0,0,.7);color:#fff;border:none;font-size:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;line-height:1;padding:0;';
            btn.innerHTML = '<i class="ri-close-line"></i>';
            (function (idx) {
                btn.onclick = function () {
                    selectedFiles.splice(idx, 1);
                    syncInput(); renderPreviews(); updateCounter();
                };
            })(i);
            wrap.appendChild(img); wrap.appendChild(btn);
            mp.appendChild(wrap);
        });
        updateCounter();
        updateSubmit();
    }

    if (ta) {
        ta.addEventListener('input', function () {
            if (cc) {
                cc.textContent = ta.value.length + '/280';
                cc.className = ta.value.length > 260 ? 'text-danger small' : 'text-secondary small';
            }
            updateSubmit();
        });
    }

    if (mi) {
        mi.addEventListener('change', function () {
            var incoming = Array.from(mi.files);
            var slots = MAX - selectedFiles.length;
            if (slots <= 0) {
                if (window.showToast) showToast('Maksimal ' + MAX + ' gambar per balasan.', 'error');
                mi.value = ''; return;
            }
            if (incoming.length > slots) {
                if (window.showToast) showToast('Hanya ' + slots + ' gambar lagi yang bisa ditambahkan.', 'info');
            }
            selectedFiles = selectedFiles.concat(incoming.slice(0, slots));
            syncInput(); renderPreviews();
        });
    }
})();
</script>
@endpush

</x-layouts.app>
