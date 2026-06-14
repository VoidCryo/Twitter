<?php

namespace App\Services;

use App\Models\User;

class FollowService
{

    public function toggle(User $actor, User $target): bool {
        abort_if($actor->id === $target->id, 422, 'Tidak bisa follow diri sendiri');

        $actor->followings()->toggle($target->id);

        return $actor->followings()->where('following_id', $target->id)->exists();
    }

    public function getSuggestions(User $user, int $limit = 5) {
        $followingIds = $user->followings()->allRelatedIds()->push($user->id)->toArray();

        return User::with('profile')
            ->whereNotIn('id', $followingIds)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getFollowers(User $user, int $perPage = 20) {
        return $user->followers()->with('profile')->paginate($perPage);
    }

    public function getFollowings(User $user, int $perPage = 20) {
        return $user->followings()->with('profile')->paginate($perPage);
    }
}
