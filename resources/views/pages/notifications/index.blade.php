<x-layouts.app title="Notifikasi · Tenebris">

<div class="mx-auto" style="max-width:600px;">
    <div class="sticky-top bg-white border-bottom px-3 py-3" style="top:52px;z-index:89;">
        <span class="fw-bold fs-5">Notifikasi</span>
    </div>

    @forelse($notifications as $notif)
        @php
            $type     = $notif->type->value;
            $actor    = $notif->actor;
            $isUnread = is_null($notif->read_at);

            $iconMap = [
                'like'     => ['class' => 'text-danger',  'bg' => 'bg-danger',  'icon' => 'ri-heart-fill'],
                'replies'  => ['class' => 'text-primary', 'bg' => 'bg-primary', 'icon' => 'ri-chat-1-fill'],
                'follower' => ['class' => 'text-purple',  'bg' => 'bg-info',    'icon' => 'ri-user-follow-fill'],
                'post'     => ['class' => 'text-success', 'bg' => 'bg-success', 'icon' => 'ri-repeat-2-line'],
            ];
            $ic = $iconMap[$type] ?? ['class' => 'text-secondary', 'bg' => 'bg-secondary', 'icon' => 'ri-notification-4-fill'];

            $msgMap = [
                'like'     => ($actor?->profile?->display_name ?? $actor?->name ?? 'Seseorang') . ' menyukai postmu',
                'replies'  => ($actor?->profile?->display_name ?? $actor?->name ?? 'Seseorang') . ' membalas postmu',
                'follower' => ($actor?->profile?->display_name ?? $actor?->name ?? 'Seseorang') . ' mulai mengikutimu',
                'post'     => ($actor?->profile?->display_name ?? $actor?->name ?? 'Seseorang') . ' merepost postmu',
            ];
            $msg = $msgMap[$type] ?? 'Notifikasi baru';

            $linkUrl = $notif->post_id
                ? route('post', $notif->post_id)
                : ($notif->actor_id ? route('profile', $notif->actor) : '#');
        @endphp
        <a href="{{ $linkUrl }}" class="d-flex gap-3 px-3 py-3 border-bottom text-decoration-none text-dark {{ $isUnread ? 'bg-primary bg-opacity-10' : '' }}">
            <div class="rounded-circle {{ $ic['bg'] }} bg-opacity-10 {{ $ic['class'] }} d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;font-size:18px;">
                <i class="{{ $ic['icon'] }}"></i>
            </div>
            <div class="flex-grow-1" style="font-size:15px;line-height:1.4;">
                @if($actor)
                    @php $actorProfile = $actor->profile; @endphp
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if($actorProfile?->avatar)
                            <img src="{{ Storage::url($actorProfile->avatar) }}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-secondary fw-semibold" style="width:28px;height:28px;font-size:12px;">{{ strtoupper(substr($actor->profile?->display_name ?? $actor->name, 0, 1)) }}</div>
                        @endif
                    </div>
                @endif
                <div>{{ $msg }}</div>
                @if($notif->post?->content)
                    <div class="text-secondary small mt-1">{{ Str::limit($notif->post->content, 60) }}</div>
                @endif
                <div class="text-secondary small mt-1">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
        </a>
    @empty
        <div class="text-center py-5 text-secondary">
            <i class="ri-notification-4-line d-block fs-1 mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada notifikasi.</p>
        </div>
    @endforelse
</div>

</x-layouts.app>
