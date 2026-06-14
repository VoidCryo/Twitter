<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use App\Services\FollowService;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
        private readonly FollowService $followService
    ) {}

    public function index(User $user): View
    {
        $user = $this->profileService->getUser($user->id);
        $authUser = Auth::user();

        $posts      = $this->profileService->getUserPosts($user);
        $likedPosts = $this->profileService->getLikedPosts($user);
        $tab        = request()->input('tab', 'posts');

        $newUsers = $this->followService->getSuggestions($user, 5);

        return view('pages.profile.show', compact('user', 'authUser', 'newUsers', 'posts', 'likedPosts', 'tab'));
    }

    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');

        return view('pages.profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validated();

        $this->profileService->updateProfile($user, $validated);

        if ($request->hasFile('avatar')) {
            $this->profileService->updateAvatar($user, $request->file('avatar'));
        }

        if ($request->hasFile('banner')) {
            $this->profileService->updateBanner($user, $request->file('banner'));
        }

        return redirect()->route('profile', $user)->with('success', 'Profil berhasil diperbarui!');
    }
}
