<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostInteractionsController extends Controller
{
    public function index(Request $request, Post $post): View
    {
        $authUser = Auth::user();
        $tab      = $request->input('tab', 'likes');
        $post->loadCount(['likedBy', 'reposts', 'replies']);

        $likers    = $post->likedBy()->with('profile')->paginate(20);
        $reposters = User::with('profile')
            ->whereHas('posts', fn($q) => $q->where('repost_of_id', $post->id))
            ->paginate(20);

        return view('pages.post.interactions', compact('post', 'authUser', 'tab', 'likers', 'reposters'));
    }
}
