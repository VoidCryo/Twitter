<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function getUser(string|int $identifier): User {
        if (is_numeric($identifier)) {
            return User::with('profile')->withCount(['followings', 'followers', 'topLevelPosts as posts_count'])->findOrFail($identifier);
        }

        return User::with('profile')->withCount(['followings', 'followers', 'topLevelPosts as posts_count'])->where('name', $identifier)->firstOrFail();
    }

    public function getUserPosts(User $user, int $perPage = 15): LengthAwarePaginator {
        return Post::with([
                'postMedia',
                'user.profile',
                'repost_of.postMedia',
                'repost_of.user.profile',
                'repost_of' => fn($q) => $q->withCount(['likedBy', 'reposts', 'replies']),
            ])
            ->withCount(['likedBy', 'reposts', 'replies'])
            ->where('user_id', $user->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate($perPage);
    }

    public function getLikedPosts(User $user, int $perPage = 15): LengthAwarePaginator {
        return $user->likedPosts()
            ->with([
                'postMedia',
                'user.profile',
                'repost_of.postMedia',
                'repost_of.user.profile',
                'repost_of' => fn($q) => $q->withCount(['likedBy', 'reposts', 'replies']),
            ])
            ->withCount(['likedBy', 'reposts', 'replies'])
            ->whereNull('parent_id')
            ->orderByPivot('created_at', 'desc')
            ->paginate($perPage);
    }

    public function updateProfile(User $user, array $data): void {
        DB::transaction(function () use ($user, $data) {
            $allowedFields = ['display_name', 'bio', 'location', 'birthday'];
            $profileData = [];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $value = $data[$field];
                    $profileData[$field] = (is_string($value) && trim($value) === '') ? null : $value;
                }
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        });
    }

    public function updateAvatar(User $user, UploadedFile $file): string {
        /** @var Profile $profile */
        $profile = $user->profile();

        if ($profile && $profile->avatar) {
            Storage::disk('public')->delete($profile->avatar);
        }

        $path = $file->store('avatars', 'public');

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['avatar' => $path]
        );

        return $path;
    }

    public function updateBanner(User $user, UploadedFile $file): string {
        /** @var Profile $profile */
        $profile = $user->profile();

        if ($profile && $profile->banner) {
            Storage::disk('public')->delete($profile->banner);
        }

        $path = $file->store('banners', 'public');

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['banner' => $path]
        );

        return $path;
    }
}
