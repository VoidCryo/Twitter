<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ServFollowController extends Controller
{
    public function toggle(User $user): RedirectResponse {
        /** @var User $authUser */
        $authUser = Auth::user();
        $authUser->toggleFollowing($user);
        return back();
    }
}
