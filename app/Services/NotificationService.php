<?php

namespace App\Services;

use App\Enum\NotificationType;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{

    public function getNotifications(User $user, int $perPage = 20): LengthAwarePaginator {
        return Notification::with(['actor.profile', 'post'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function markAllRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function markRead(User $user, Notification $notification): void {
        abort_if($notification->user_id !== $user->id, 403);
        $notification->update(['read_at' => now()]);
    }

    public function unreadCount(User $user): int {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function notifyLike(User $actor, Post $post): void {
        if ($post->user_id === $actor->id) return;

        Notification::updateOrCreate(
            [
                'user_id'  => $post->user_id,
                'actor_id' => $actor->id,
                'post_id'  => $post->id,
                'type'     => NotificationType::Like,
            ],
            [
                'read_at'    => null,
                'created_at' => now(),
            ]
        );
    }

    public function notifyReply(User $actor, Post $post, Post $reply): void
    {
        if ($post->user_id === $actor->id) return;

        Notification::create([
            'user_id'  => $post->user_id,
            'actor_id' => $actor->id,
            'post_id'  => $reply->id,
            'type'     => NotificationType::Replies,
        ]);
    }

    public function notifyFollow(User $actor, User $target): void
    {
        if ($actor->id === $target->id) return;

        Notification::updateOrCreate(
            [
                'user_id'  => $target->id,
                'actor_id' => $actor->id,
                'type'     => NotificationType::Follower,
            ],
            [
                'read_at'    => null,
                'created_at' => now(),
            ]
        );
    }

    public function removeNotifyLike(User $actor, Post $post): void
    {
        Notification::where([
            'user_id'  => $post->user_id,
            'actor_id' => $actor->id,
            'post_id'  => $post->id,
            'type'     => NotificationType::Like,
        ])->delete();
    }

    public function removeNotifyFollow(User $actor, User $target): void
    {
        Notification::where([
            'user_id'  => $target->id,
            'actor_id' => $actor->id,
            'type'     => NotificationType::Follower,
        ])->delete();
    }
}
