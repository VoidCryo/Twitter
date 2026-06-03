<?php

namespace App\Http\Controllers\page;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View {
        $key = $request->input('query');

        $user = User::with('profile')
            ->whereFullText('name', $key)
            ->paginate(15, ['*'], 'page_user');

        $post = Post::with('postMedia', 'user.profile')
            ->whereFullText('content', $key)
            ->paginate(15, ['*'], 'page_post');

        return view('page.search', compact('user', 'post', 'key'));
    }
}
