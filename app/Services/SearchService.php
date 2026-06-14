<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    /**
     * Cari user berdasarkan keyword.
     *
     * @param  string $keyword
     * @param  int    $perPage
     * @return LengthAwarePaginator
     */
    public function searchUsers(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return User::with('profile')
            ->whereFullText('name', $keyword)
            ->paginate($perPage, ['*'], 'page_user');
    }

    /**
     * Cari post berdasarkan keyword.
     *
     * @param  string $keyword
     * @param  int    $perPage
     * @return LengthAwarePaginator
     */
    public function searchPosts(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return Post::with(['postMedia', 'user.profile'])
            ->whereFullText('content', $keyword)
            ->whereNull('parent_id')
            ->latest()
            ->paginate($perPage, ['*'], 'page_post');
    }
}
