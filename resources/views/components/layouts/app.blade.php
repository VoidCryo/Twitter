<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tenebris' }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{-- Flash alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-floating alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('failed'))
        <div class="alert alert-danger alert-floating alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('failed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="app-layout">
        {{-- SIDEBAR KIRI (tablet/desktop) --}}
        <aside class="sidebar">
            <a href="{{ route('home') }}" class="sidebar-logo">
                <i class="ri-twitter-x-line"></i>
            </a>

            <nav class="sidebar-nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="ri-home-{{ request()->routeIs('home') ? 'fill' : 'line' }}"></i>
                    <span class="nav-label">Beranda</span>
                </a>
                <a href="#" class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                    <i class="ri-search-line"></i>
                    <span class="nav-label">Jelajah</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="ri-notification-line"></i>
                    <span class="nav-label">Notifikasi</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="ri-mail-line"></i>
                    <span class="nav-label">Pesan</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="ri-user-line"></i>
                    <span class="nav-label">Profil</span>
                </a>
            </nav>

            <x-forms.button
                variant="post-sidebar"
                data-bs-toggle="modal"
                data-bs-target="#postModal"
                title="Post"
            >
                <i class="ri-add-line d-block d-lg-none"></i>
                <span class="d-none d-lg-inline">Post</span>
            </x-forms.button>

            @auth
            <div class="sidebar-user">
                <div class="post-avatar-placeholder" style="width:36px;height:36px;font-size:.85rem;flex-shrink:0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info flex-grow-1 overflow-hidden">
                    <div class="post-username text-truncate" style="font-size:.85rem">{{ auth()->user()->name }}</div>
                    <div class="post-handle text-truncate" style="font-size:.78rem">@{{ auth()->user()->name }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="d-none d-lg-block">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-secondary p-0" title="Logout">
                        <i class="ri-logout-box-r-line fs-5"></i>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        {{-- MAIN FEED --}}
        <main class="feed-col">
            {{ $slot }}
        </main>

        {{-- SIDEBAR KANAN --}}
        @isset($rightSidebar)
        <div class="widget-sidebar">
            {{ $rightSidebar }}
        </div>
        @endisset
    </div>

    {{-- BOTTOM NAV (mobile only) --}}
    @auth
    <nav class="bottom-nav" aria-label="Navigasi utama">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="ri-home-{{ request()->routeIs('home') ? 'fill' : 'line' }}"></i>
        </a>
        <a href="#" class="bottom-nav-item {{ request()->routeIs('search') ? 'active' : '' }}">
            <i class="ri-search-line"></i>
        </a>
        <button class="bottom-nav-item post-btn" data-bs-toggle="modal" data-bs-target="#postModal" aria-label="Buat post">
            <i class="ri-add-line"></i>
        </button>
        <a href="#" class="bottom-nav-item">
            <i class="ri-notification-line"></i>
        </a>
        <a href="#" class="bottom-nav-item">
            <i class="ri-user-line"></i>
        </a>
    </nav>
    @endauth

    {{-- MODAL COMPOSE POST --}}
    @auth
    <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-modal="true" role="dialog" data-bs-backdrop="true" data-bs-keyboard="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-1">
                    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex gap-3">
                            <div class="post-avatar-placeholder">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <textarea name="content" class="compose-textarea"
                                          placeholder="Apa yang sedang terjadi?!"
                                          maxlength="256" required rows="3"></textarea>
                                <div id="modalMediaPreview" class="compose-media-preview d-none"></div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label class="text-secondary" style="cursor:pointer" title="Tambah media">
                                        <i class="ri-image-line fs-5" style="color:var(--brand)"></i>
                                        <input type="file" name="media[]" accept="image/*" multiple class="d-none"
                                               onchange="previewImages(this, 'modalMediaPreview')">
                                    </label>
                                    <x-forms.button type="submit" variant="brand" class="rounded-pill fw-bold px-4">
                                        Post
                                    </x-forms.button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endauth

    {{-- DELETE CONFIRM MODAL --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="true" data-bs-keyboard="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:340px">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div style="width:52px;height:52px;background:#fff0f3;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                        <i class="ri-delete-bin-2-line" style="font-size:1.6rem;color:#e0245e"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Hapus Post?</h6>
                    <p class="text-secondary mb-0" style="font-size:.9rem">Post akan dihapus permanen dan tidak bisa dikembalikan.</p>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex gap-2 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill fw-semibold flex-fill"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn rounded-pill fw-bold flex-fill"
                            style="background:#e0245e;color:#fff"
                            id="deleteConfirmBtn">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    {{-- LIGHTBOX --}}
    <div id="lightboxOverlay" role="dialog" aria-modal="true">
        <button id="lbClose" onclick="closeLightbox()" title="Tutup">&#10005;</button>
        <button class="lb-btn" id="lbPrev" onclick="lbMove(-1)">&#8249;</button>
        <img id="lbImg" src="" alt="preview">
        <button class="lb-btn" id="lbNext" onclick="lbMove(1)">&#8250;</button>
        <div id="lbCounter"></div>
        <div id="lbDots"></div>
    </div>

    @stack('scripts')
</body>
</html>
