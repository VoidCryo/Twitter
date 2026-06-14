<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\StoreReplyRequest;
use App\Models\Post;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService         $postService,
        private readonly NotificationService $notificationService,
    ) {}

    public function store(StorePostRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user      = Auth::user();
        $validated = $request->validated();
        $mediaFiles = $request->hasFile('media') ? $request->file('media') : [];

        $post = $this->postService->createPost($user, $validated['content'] ?? null, $mediaFiles);

        return redirect()->route('post', $post)->with('success', 'Post berhasil dibuat!');
    }

    public function like(Post $post): RedirectResponse
    {
        /** @var User $user */
        $user  = Auth::user();
        $liked = $this->postService->toggleLike($user, $post);

        if ($liked) {
            $this->notificationService->notifyLike($user, $post);
        } else {
            $this->notificationService->removeNotifyLike($user, $post);
        }

        return back();
    }

    public function repost(Post $post): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->postService->toggleRepost($user, $post);

        return back();
    }

    public function reply(StoreReplyRequest $request, Post $post): RedirectResponse
    {
        /** @var User $user */
        $user       = Auth::user();
        $validated  = $request->validated();
        $mediaFiles = $request->hasFile('media') ? $request->file('media') : [];

        $reply = $this->postService->createReply($user, $post, $validated['content'] ?? null, $mediaFiles);

        $this->notificationService->notifyReply($user, $post, $reply);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        /** @var User $user */
        $user    = Auth::user();
        $isReply = !is_null($post->parent_id);

        $this->postService->deletePost($user, $post);

        if ($isReply) {
            return back()->with('success', 'Post berhasil dihapus.');
        }

        return redirect()->route('home')->with('success', 'Post berhasil dihapus.');
    }
}
