<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    public function index(Request $request, Post $post): View
    {
        $authUser = Auth::user();
        $post->loadCount(['likedBy', 'reposts', 'replies']);
        $replies = $this->postService->getReplies($post, $request->input('page', 1), 15);

        return view('pages.post.index', compact('post', 'authUser', 'replies'));
    }
}
