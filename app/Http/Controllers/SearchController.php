<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(private readonly SearchService $searchService) {}

    public function index(Request $request): View
    {
        $key  = trim($request->input('query', ''));
        $user = null;
        $post = null;

        if ($key !== '') {
            $user = $this->searchService->searchUsers($key);
            $post = $this->searchService->searchPosts($key);
        }

        return view('pages.search', compact('user', 'post', 'key'));
    }
}
