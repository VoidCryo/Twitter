<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tenebris' }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-white text-dark pb-5">

@php
    $authUser    = auth()->user();
    $authProfile = $authUser?->profile;
    // Hitung unread notif sekali di layout — hindari N+1 di navbar
    $navUnread   = $authUser
        ? \App\Models\Notification::where('user_id', $authUser->id)->whereNull('read_at')->count()
        : 0;
@endphp

{{-- Header --}}
<header class="sticky-top bg-white border-bottom d-flex align-items-center justify-content-between px-3" style="height:52px;z-index:100;">
    <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-primary fw-bold fs-5 text-decoration-none">
        <i class="ri-centos-line fs-4"></i>
        Tenebris
    </a>
    @auth
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link text-secondary p-1 fs-5">
            <i class="ri-logout-box-r-line"></i>
        </button>
    </form>
    @endauth
</header>

{{ $slot }}

@auth
<button class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow position-fixed"
        style="width:52px;height:52px;bottom:72px;right:16px;z-index:150;font-size:26px;"
        data-bs-toggle="modal" data-bs-target="#modalCreatePost" title="Buat Post">
    <i class="ri-add-line"></i>
</button>
@endauth

@auth
<nav class="fixed-bottom bg-white border-top d-flex align-items-center justify-content-around px-2" style="height:56px;z-index:200;">
    <a href="{{ route('home') }}" class="d-flex flex-column align-items-center justify-content-center flex-fill h-100 text-decoration-none {{ request()->routeIs('home') ? 'text-dark' : 'text-secondary' }}" style="font-size:26px;">
        <i class="{{ request()->routeIs('home') ? 'ri-home-5-fill' : 'ri-home-5-line' }}"></i>
    </a>
    <a href="{{ route('search') }}" class="d-flex flex-column align-items-center justify-content-center flex-fill h-100 text-decoration-none {{ request()->routeIs('search') ? 'text-dark' : 'text-secondary' }}" style="font-size:26px;">
        <i class="{{ request()->routeIs('search') ? 'ri-search-fill' : 'ri-search-line' }}"></i>
    </a>
    <a href="{{ route('notifications') }}" class="d-flex flex-column align-items-center justify-content-center flex-fill h-100 text-decoration-none position-relative {{ request()->routeIs('notifications') ? 'text-dark' : 'text-secondary' }}" style="font-size:26px;">
        <i class="{{ request()->routeIs('notifications') ? 'ri-notification-4-fill' : 'ri-notification-4-line' }}"></i>
        @if($navUnread > 0)
            <span class="badge bg-primary rounded-pill position-absolute" style="top:8px;right:calc(50% - 18px);font-size:10px;">{{ $navUnread > 9 ? '9+' : $navUnread }}</span>
        @endif
    </a>
    <a href="{{ route('profile', auth()->user()) }}" class="d-flex flex-column align-items-center justify-content-center flex-fill h-100 text-decoration-none {{ request()->routeIs('profile') ? 'text-dark' : 'text-secondary' }}" style="font-size:26px;">
        @if($authProfile?->avatar)
            <img src="{{ Storage::url($authProfile->avatar) }}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;">
        @else
            <i class="{{ request()->routeIs('profile') ? 'ri-user-fill' : 'ri-user-line' }}"></i>
        @endif
    </a>
</nav>
@endauth

{{-- ===== MODAL CREATE POST ===== --}}
@auth
<div class="modal fade" id="modalCreatePost" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 overflow-hidden">
            <div class="modal-header border-bottom px-3 py-2">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <span class="ms-2 fw-semibold" style="font-size:16px;">Post Baru</span>
            </div>
            <div class="modal-body p-3">
                <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" id="formCreatePost">
                    @csrf
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            @if($authProfile?->avatar)
                                <img src="{{ Storage::url($authProfile->avatar) }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-secondary" style="width:40px;height:40px;">{{ strtoupper(substr($authProfile?->display_name ?? $authUser->name, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <textarea class="form-control border-0 p-0 shadow-none bg-transparent fs-5" name="content"
                                      placeholder="Apa yang sedang terjadi?!" rows="4" maxlength="280" id="postContent"
                                      style="resize:none;min-height:80px;"></textarea>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="postMedia" class="text-primary mb-0" style="cursor:pointer;">
                                        <i class="ri-image-line fs-5"></i>
                                        <input type="file" name="media[]" id="postMedia" class="d-none" multiple accept="image/*">
                                    </label>
                                    <span id="postMediaCount" class="text-secondary small"></span>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span id="charCount" class="text-secondary small">0/280</span>
                                    <button type="submit" class="btn btn-primary rounded-pill btn-sm px-3 fw-semibold" id="postSubmitBtn" disabled>Post</button>
                                </div>
                            </div>
                            <div id="mediaPreview" class="d-flex gap-2 flex-wrap mt-2"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth

{{-- ===== MODAL CONFIRM DELETE ===== --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body p-4 text-center">
                <i class="ri-delete-bin-line text-danger fs-1 mb-2 d-block"></i>
                <p class="fw-semibold mb-0">Hapus post ini?</p>
                <p class="text-secondary small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top-0 p-3 pt-0 d-flex gap-2">
                <button type="button" class="btn btn-light flex-fill rounded-pill fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger flex-fill rounded-pill fw-semibold" id="deleteConfirmBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== TOAST CONTAINER ===== --}}
<div id="toastContainer" aria-live="polite" aria-atomic="true"
     style="position:fixed;top:64px;left:50%;transform:translateX(-50%);z-index:9999;width:min(360px,calc(100vw - 24px));display:flex;flex-direction:column;gap:8px;pointer-events:none;">
</div>

{{-- ===== LIGHTBOX ===== --}}
<div id="lbOverlay" role="dialog" aria-modal="true" aria-label="Lightbox gambar">
    <button id="lbClose" onclick="lbClose()" aria-label="Tutup"><i class="ri-close-line"></i></button>
    <div id="lbCounter"></div>
    <div id="lbTrackWrap"><div id="lbTrack"></div></div>
    <button id="lbPrev" onclick="lbMove(-1)" aria-label="Sebelumnya"><i class="ri-arrow-left-s-line"></i></button>
    <button id="lbNext" onclick="lbMove(1)"  aria-label="Selanjutnya"><i class="ri-arrow-right-s-line"></i></button>
    <div id="lbDots"></div>
</div>

<script>
/* ── TOAST (dipanggil dari Blade — harus setelah DOM siap) ── */
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success')) showToast(@json(session('success')), 'success'); @endif
    @if(session('error') && !request()->routeIs('post'))   showToast(@json(session('error')),   'error');   @endif
    @if($errors->any() && !request()->routeIs('post'))     showToast(@json($errors->first()),   'error');   @endif
});

/* ── CREATE POST — char counter + media preview (max 4, removable) ── */
(function () {
    var MAX = 4;
    var ta  = document.getElementById('postContent');
    var cc  = document.getElementById('charCount');
    var sb  = document.getElementById('postSubmitBtn');
    var mi  = document.getElementById('postMedia');
    var mp  = document.getElementById('mediaPreview');
    var mc  = document.getElementById('postMediaCount');
    var selectedFiles = [];

    function syncInput() {
        var dt = new DataTransfer();
        selectedFiles.forEach(function (f) { dt.items.add(f); });
        if (mi) mi.files = dt.files;
    }
    function updateSubmit() {
        var hasText  = ta && ta.value.trim().length > 0;
        var hasMedia = selectedFiles.length > 0;
        if (sb) sb.disabled = !(hasText || hasMedia);
    }
    function updateCounter() {
        if (!mc) return;
        mc.textContent = selectedFiles.length > 0 ? selectedFiles.length + '/' + MAX : '';
        mc.className   = selectedFiles.length >= MAX ? 'text-danger small fw-semibold' : 'text-secondary small';
    }
    function renderPreviews() {
        if (!mp) return;
        mp.innerHTML = '';
        selectedFiles.forEach(function (f, i) {
            var wrap = document.createElement('div');
            wrap.className = 'mp-thumb';
            var img = document.createElement('img');
            img.src = URL.createObjectURL(f);
            var btn = document.createElement('button');
            btn.type = 'button'; btn.className = 'mp-remove';
            btn.innerHTML = '<i class="ri-close-line"></i>';
            (function (idx) {
                btn.onclick = function () {
                    selectedFiles.splice(idx, 1);
                    syncInput(); renderPreviews(); updateCounter(); updateSubmit();
                };
            })(i);
            wrap.appendChild(img); wrap.appendChild(btn);
            mp.appendChild(wrap);
        });
        updateCounter(); updateSubmit();
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
                if (window.showToast) showToast('Maksimal ' + MAX + ' gambar per post.', 'error');
                mi.value = ''; return;
            }
            if (incoming.length > slots) showToast('Hanya ' + slots + ' gambar lagi bisa ditambahkan (maks ' + MAX + ').', 'info');
            selectedFiles = selectedFiles.concat(incoming.slice(0, slots));
            syncInput(); renderPreviews();
        });
    }
    var modal = document.getElementById('modalCreatePost');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            selectedFiles = [];
            if (ta) ta.value = '';
            if (cc) cc.textContent = '0/280';
            if (mp) mp.innerHTML = '';
            if (mi) mi.value = '';
            updateCounter();
            if (sb) sb.disabled = true;
        });
    }
})();
</script>

@stack('scripts')
</body>
</html>
