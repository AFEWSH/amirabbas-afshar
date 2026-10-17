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
        $translatedProjectSlugs = Project::published()->whereNotNull('title_fa')->where('title_fa', '!=', '')
            ->whereNotNull('summary_fa')->where('summary_fa', '!=', '')->pluck('slug')->flip();
        $posts = BlogPost::published()->orderBy('id')->get(['slug', 'updated_at']);
        $translatedPostSlugs = BlogPost::published()->whereNotNull('title_fa')->where('title_fa', '!=', '')
            ->whereNotNull('excerpt_fa')->where('excerpt_fa', '!=', '')
            ->whereNotNull('content_fa')->where('content_fa', '!=', '')->pluck('slug')->flip();

        return response()->view('sitemap', compact('projects', 'translatedProjectSlugs', 'posts', 'translatedPostSlugs'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
