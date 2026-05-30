<x-layouts.app title="Beranda — Tenebris">
    <x-slot:rightSidebar>
        <div class="widget-box">
            <h2 class="widget-title">Siapa yang diikuti</h2>
            @if($newUsers->count() > 0)
                @foreach($newUsers as $newUser)
                <div class="who-to-follow-item">
                    <div class="post-avatar-placeholder" style="width:38px;height:38px;font-size:.9rem">
                        {{ strtoupper(substr($newUser->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden flex-grow-1">
                        <div class="post-username text-truncate" style="font-size:.875rem">{{ $newUser->name }}</div>
                        <div class="post-handle text-truncate" style="font-size:.8rem">{{ '@' . $newUser->name }}</div>
                    </div>
                    <form method="POST" action="{{ route('user.follow', $newUser) }}" onclick="event.stopPropagation()">
                        @csrf
                        <button type="submit" class="btn-follow">
                            {{ $user->isFollowing($newUser) ? 'Unfollow' : 'Follow' }}
                        </button>
                    </form>
                </div>
                @endforeach
            @else
                <p class="text-secondary" style="font-size:.875rem">Tidak ada saran untuk saat ini.</p>
            @endif
        </div>
        <p style="color:#8b98a5;font-size:.8rem;padding:.5rem">© 2025 Tenebris · Dibuat dengan ❤</p>
    </x-slot:rightSidebar>

    {{-- Tabs --}}
    <div class="feed-header">
        <ul class="nav feed-tabs mb-0" id="feedTab">
            <li class="nav-item flex-fill text-center">
                <a class="nav-link active d-block" id="for-you-tab" data-bs-toggle="tab"
                   href="#for-you" role="tab">Untuk Kamu</a>
            </li>
            <li class="nav-item flex-fill text-center">
                <a class="nav-link d-block" id="following-tab" data-bs-toggle="tab"
                   href="#following" role="tab">Mengikuti</a>
            </li>
        </ul>
    </div>

    {{-- Feed --}}
    <div class="tab-content" id="feedTabContent">

        {{-- FOR YOU --}}
        <div class="tab-pane fade show active" id="for-you" role="tabpanel">
            <div id="feed-for-you">
                @forelse($posts as $post)
                    <x-post-card :post="$post" :authUser="$user" />
                @empty
                    <div class="text-center py-5" style="color:#536471">
                        <i class="ri-quill-pen-line" style="font-size:2.5rem;opacity:.4"></i>
                        <p class="mt-2 mb-0">Belum ada post. Mulai posting sesuatu!</p>
                    </div>
                @endforelse
            </div>
            @if($posts->hasMorePages())
            <div class="load-more-wrap" id="wrap-for-you">
                <button class="btn-load-more"
                        data-tab="for-you"
                        data-next="2"
                        data-url="{{ route('home.load-more') }}"
                        data-container="feed-for-you"
                        data-wrap="wrap-for-you"
                        onclick="loadMoreFeed(this)">
                    <i class="ri-refresh-line me-1"></i> Muat Lebih Banyak
                </button>
            </div>
            @endif
        </div>

        {{-- FOLLOWING --}}
        <div class="tab-pane fade" id="following" role="tabpanel">
            <div id="feed-following">
                @forelse($followingPosts as $post)
                    <x-post-card :post="$post" :authUser="$user" />
                @empty
                    <div class="text-center py-5" style="color:#536471">
                        <i class="ri-user-follow-line" style="font-size:2.5rem;opacity:.4"></i>
                        <p class="mt-2 mb-0">Ikuti orang-orang untuk melihat post mereka di sini.</p>
                    </div>
                @endforelse
            </div>
            @if($followingPosts->hasMorePages())
            <div class="load-more-wrap" id="wrap-following">
                <button class="btn-load-more"
                        data-tab="following"
                        data-next="2"
                        data-url="{{ route('home.load-more') }}"
                        data-container="feed-following"
                        data-wrap="wrap-following"
                        onclick="loadMoreFeed(this)">
                    <i class="ri-refresh-line me-1"></i> Muat Lebih Banyak
                </button>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
