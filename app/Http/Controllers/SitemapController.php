<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController
{
    public function __invoke(): Response
    {
        $projects = Project::published()->orderBy('id')->get(['slug', 'updated_at']);
        $posts = BlogPost::published()->orderBy('id')->get(['slug', 'updated_at']);

        return response()->view('sitemap', compact('projects', 'posts'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
