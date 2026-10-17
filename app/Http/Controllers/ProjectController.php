<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectController
{
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Project::published()
                ->with(['category', 'technologies', 'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
                ->orderBy('sort_order')->orderByDesc('published_at')->orderBy('id')
                ->paginate(12),
        ]);
    }

    public function show(string $slug): View
    {
        $project = Project::published()->where('slug', $slug)
            ->with(['category', 'technologies', 'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }
}
