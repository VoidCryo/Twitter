<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View {
        /** @var User $user */
        $user = Auth::user();

        $followingIds = $user->followings()->allRelatedIds();

        $newUsers = User::whereNotIn('id', $followingIds->push($user->id)->toArray())
            ->limit(5)
            ->get();

        $posts = Post::with(['postMedia', 'user.profile'])
            ->whereNull('parent_id')
            ->orderByDesc('created_at')
            ->paginate(15);

        $followingPosts = Post::with(['postMedia', 'user.profile'])
            ->whereIn('user_id', $followingIds)
            ->whereNotIn('user_id', [$user->id])
            ->whereNull('parent_id')
            ->latest()
            ->paginate(15);

        return view('page.home', compact('user', 'newUsers', 'posts', 'followingPosts'));
    }

    public function loadMore(Request $request) {
        /** @var User $user */
        $user = Auth::user();
        $tab  = $request->input('tab', 'for-you');
        $page = max(1, (int) $request->input('page', 2));

        if ($tab === 'following') {
            $followingIds = $user->followings()->allRelatedIds();
            $posts = Post::with(['postMedia', 'user.profile', 'repost_of.postMedia', 'repost_of.user.profile'])
                ->whereIn('user_id', $followingIds)
                ->whereNull('parent_id')
                ->latest()
                ->paginate(15, ['*'], 'page', $page);
        } else {
            $posts = Post::with(['postMedia', 'user.profile', 'repost_of.postMedia', 'repost_of.user.profile'])
                ->whereNull('parent_id')
                ->orderByDesc('created_at')
                ->paginate(15, ['*'], 'page', $page);
        }

        $html = '';
        foreach ($posts as $post) {
            $html .= view('components.post-card', [
                'post'     => $post,
                'authUser' => $user,
            ])->render();
        }

        return response()->json([
            'html'     => trim($html),
            'hasMore'  => $posts->hasMorePages(),
            'nextPage' => $page + 1,
        ]);
    }
}
