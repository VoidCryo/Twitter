<?php

namespace App\Http\Controllers\page;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Post $post): View {
        return view('page.post', compact('post'));
    }

    public function loadMoreReplies(Request $request, Post $post) {
        $user  = Auth::user();
        if (!$user) abort(401);
        $page  = (int) $request->input('page', 2);

        $replies = $post->replies()
            ->with(['postMedia', 'user.profile'])
            ->latest()
            ->paginate(15, ['*'], 'page', $page);

        $html = '';
        foreach ($replies as $reply) {
            $html .= view('components.post-card', [
                'post'     => $reply,
                'authUser' => $user,
            ])->render();
        }
        $html = trim($html);

        return response()->json([
            'html'     => $html,
            'hasMore'  => $replies->hasMorePages(),
            'nextPage' => $page + 1,
        ])->header('Content-Type', 'application/json');
    }
}
