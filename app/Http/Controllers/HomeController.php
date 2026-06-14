<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FollowService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly PostService   $postService,
        private readonly FollowService $followService,
    ) {}

    public function index(Request $request): View {
        $user = Auth::user();
        $tab  = $request->input('tab', 'for-you');

        $posts = $tab === 'following'
            ? $this->postService->getFollowingFeed($user, 15)
            : $this->postService->getForYouFeed(15);

        $newUsers = $this->followService->getSuggestions($user, 5);

        return view('pages.home', compact('user', 'posts', 'newUsers', 'tab'));
    }
}
