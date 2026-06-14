<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function createPost(User $user, ?string $content, ?array $mediaFiles = []): Post {
        return DB::transaction(function () use ($user, $content, $mediaFiles) {
            /** @var Post $post */
            $post = $user->posts()->create([
                'content' => $content,
            ]);

            $this->attachMedia($post, $mediaFiles);

            return $post;
        });
    }

    public function createReply(User $user, Post $parent, ?string $content, ?array $mediaFiles = []): Post {
        return DB::transaction(function () use ($user, $parent, $content, $mediaFiles) {
            /** @var Post $reply */
            $reply = $user->posts()->create([
                'content'   => $content,
                'parent_id' => $parent->id,
                'root_id'   => $parent->root_id ?? $parent->id,
            ]);

            $this->attachMedia($reply, $mediaFiles);

            return $reply;
        });
    }

    public function toggleRepost(User $user, Post $post): bool {
        return DB::transaction(function () use ($user, $post) {
            $existing = Post::where('user_id', $user->id)
                ->where('repost_of_id', $post->id)
                ->first();

            if ($existing) {
                $existing->delete();
                return false;
            }

            $post->loadMissing('repost_of');

            $rootId = $post->repost_of_id
                ? ($post->repost_of->root_id ?? $post->repost_of_id)
                : ($post->root_id ?? $post->id);

            $user->posts()->create([
                'repost_of_id' => $post->id,
                'root_id'      => $rootId,
            ]);

            return true;
        });
    }

    public function toggleLike(User $user, Post $post): bool {
        $result = $user->likedPosts()->toggle($post->id);
        return count($result['attached']) > 0;
    }

    public function deletePost(User $user, Post $post): void {
        abort_if($post->user_id !== $user->id, 403, 'Forbidden');
        $post->delete();
    }

    public function getForYouFeed(int $perPage = 15): LengthAwarePaginator {
        return Post::with([
                'postMedia',
                'user.profile',
                'repost_of.postMedia',
                'repost_of.user.profile',
                'repost_of' => fn($q) => $q->withCount(['likedBy', 'reposts', 'replies']),
            ])
            ->withCount(['likedBy', 'reposts', 'replies'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate($perPage);
    }

    public function getFollowingFeed(User $user, int $perPage = 15): LengthAwarePaginator {
        $followingIds = $user->followings()->allRelatedIds()->toArray();
        $targetUserIds = array_merge($followingIds, [$user->id]);

        return Post::with([
                'postMedia',
                'user.profile',
                'repost_of.postMedia',
                'repost_of.user.profile',
                'repost_of' => fn($q) => $q->withCount(['likedBy', 'reposts', 'replies']),
            ])
            ->withCount(['likedBy', 'reposts', 'replies'])
            ->whereIn('user_id', $targetUserIds)
            ->whereNull('parent_id')
            ->latest()
            ->paginate($perPage);
    }

    public function getReplies(Post $post, int $page = 1, int $perPage = 15): LengthAwarePaginator {
        return $post->replies()
            ->with(['postMedia', 'user.profile'])
            ->withCount(['likedBy', 'reposts', 'replies'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);
    }

    private function attachMedia(Post $post, ?array $files): void {
        if (empty($files)) return;

        foreach ($files as $file) {
            if (!$post->canAddMedia()) break;
            $path = $file->store('posts', 'public');
            $post->postMedia()->create(['image' => $path]);
        }
    }
}
