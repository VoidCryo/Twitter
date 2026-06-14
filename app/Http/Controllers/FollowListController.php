<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FollowService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FollowListController extends Controller
{
    public function __construct(private readonly FollowService $followService) {}

    public function followers(User $user): View
    {
        $authUser  = Auth::user();
        $followers = $this->followService->getFollowers($user, 20);

        return view('pages.profile.followers', compact('user', 'authUser', 'followers'));
    }

    public function following(User $user): View
    {
        $authUser  = Auth::user();
        $followings = $this->followService->getFollowings($user, 20);

        return view('pages.profile.following', compact('user', 'authUser', 'followings'));
    }
}
