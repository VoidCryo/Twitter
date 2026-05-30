<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Http\Requests\post\StorePostRequest;
use App\Http\Requests\post\StoreReplyRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ServPostController extends Controller
{
    public function store(StorePostRequest $request): RedirectResponse {
        /** @var User $user*/
        $user = Auth::user();
        $validated = $request->validated();

        /** @var Post $post */
        $post = $user->posts()->create([
            'user_id' => $user->id,
            'content' => $validated['content']
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if (!$post->canAddMedia()) break;
                $path = $file->store('posts', 'public');
                $post->postMedia()->create(['post_id' => $post->id, 'image' => $path]);
            }
        }
        return redirect()->route('post', $post);
    }

    public function like(Post $post): RedirectResponse {
        /** @var User $user*/
        $user = Auth::user();
        $user->toggleLiked($post);
        return back();
    }

    public function repost(Post $post): RedirectResponse {
        /** @var User $user*/
        $user = Auth::user();
        $alreadyReposted = Post::where('user_id', $user->id)
            ->where('repost_of_id', $post->id)
            ->exists();

        if (!$alreadyReposted) {
            $user->posts()->create([
                'repost_of_id' => $post->id,
                'root_id' => $post->root_id ?? $post->id
            ]);
        } else {
            Post::where('user_id', $user->id)
                ->where('repost_of_id', $post->id)
                ->delete();
        }
        return back();
    }

    public function reply(StoreReplyRequest $request, Post $post): RedirectResponse {
        /** @var User $user*/
        $user = Auth::user();
        $validated = $request->validated();

        /** @var Post $reply */
        $reply = $user->posts()->create([
            'content'   => $validated['content'],
            'parent_id' => $post->id,
            'root_id'   => $post->root_id ?? $post->id
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if (!$reply->canAddMedia()) break;
                $path = $file->store('posts', 'public');
                $reply->postMedia()->create(['image' => $path]);
            }
        }
        return back();
    }

    public function destroy(Post $post): RedirectResponse {
        /** @var User $user*/
        $user = Auth::user();
        if ($post->user_id !== $user->id) abort(403);
        $post->delete();
        return back();
    }
}
