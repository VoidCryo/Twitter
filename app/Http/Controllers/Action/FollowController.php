<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FollowService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function __construct(
        private readonly FollowService       $followService,
        private readonly NotificationService $notificationService,
    ) {}

    public function toggle(User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $isNowFollowing = $this->followService->toggle($authUser, $user);

        if ($isNowFollowing) {
            $this->notificationService->notifyFollow($authUser, $user);
        } else {
            $this->notificationService->removeNotifyFollow($authUser, $user);
        }

        return back();
    }
}
