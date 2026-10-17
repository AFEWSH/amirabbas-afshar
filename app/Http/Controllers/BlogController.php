<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Contracts\View\View;

class BlogController
{
    public function index(): View
    {
        return view('blog.index', [
            'posts' => BlogPost::published()->with('category')->orderByDesc('published_at')->orderByDesc('id')->paginate(12),
        ]);
    }

    public function show(string $slug): View
    {
        $post = BlogPost::published()->where('slug', $slug)->with('category')->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
