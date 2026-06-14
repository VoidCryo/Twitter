<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Ambil user beserta relasi profil.
     *
     * @param  string|int $identifier  username atau id
     * @return User
     */
    public function getUser(string|int $identifier): User
    {
        if (is_numeric($identifier)) {
            return User::with('profile')->withCount(['followings', 'followers', 'topLevelPosts as posts_count'])->findOrFail($identifier);
        }

        return User::with('profile')->withCount(['followings', 'followers', 'topLevelPosts as posts_count'])->where('name', $identifier)->firstOrFail();
    }

    /**
     * Ambil post-post milik user (tidak termasuk reply).
     *
     * @param  User $user
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getUserPosts(User $user, int $perPage = 15): LengthAwarePaginator
    {
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

    /**
     * Ambil post yang disukai user.
     *
     * @param  User $user
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getLikedPosts(User $user, int $perPage = 15): LengthAwarePaginator
    {
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

    /**
     * Update profil user (bio, location, birthday).
     *
     * @param  User  $user
     * @param  array $data
     * @return void
     */
    public function updateProfile(User $user, array $data): void
    {
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

    /**
     * Update avatar user.
     *
     * @param  User         $user
     * @param  UploadedFile $file
     * @return string path avatar baru
     */
    public function updateAvatar(User $user, UploadedFile $file): string
    {
        $path = $file->store('avatars', 'public');

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['avatar' => $path]
        );

        return $path;
    }

    /**
     * Update banner user.
     *
     * @param  User         $user
     * @param  UploadedFile $file
     * @return string path banner baru
     */
    public function updateBanner(User $user, UploadedFile $file): string
    {
        $path = $file->store('banners', 'public');

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['banner' => $path]
        );

        return $path;
    }
}
