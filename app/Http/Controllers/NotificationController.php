<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user          = Auth::user();
        $unreadCount   = $this->notificationService->unreadCount($user);
        $this->notificationService->markAllRead($user);
        $notifications = $this->notificationService->getNotifications($user, 20);

        return view('pages.notifications.index', compact('notifications', 'unreadCount'));
    }
}
