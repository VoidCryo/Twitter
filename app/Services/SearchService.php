<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    public function searchUsers(string $keyword, int $perPage = 15): LengthAwarePaginator {
        $query = User::with('profile');

        if (mb_strlen($keyword) < 6) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        } else {
            $query->whereFullText('name', $keyword);
        }

        return $query->paginate($perPage, ['*'], 'page_user');
    }

    public function searchPosts(string $keyword, int $perPage = 15): LengthAwarePaginator {
        $query = Post::with([
                'postMedia',
                'user.profile',
                'repost_of.user.profile',
                'repost_of.postMedia'
            ])
            ->withCount(['likedBy', 'reposts', 'replies'])
            ->whereNull('parent_id');

        if (mb_strlen($keyword) < 6) {
            $query->where('content', 'LIKE', '%' . $keyword . '%');
        } else {
            $query->whereFullText('content', $keyword);
        }

        return $query->latest()
            ->paginate($perPage, ['*'], 'page_post');
    }
}
