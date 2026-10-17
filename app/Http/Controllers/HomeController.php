<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Education;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ResumeFile;
use App\Models\SkillCategory;
use App\Models\SocialLink;
use App\Models\WorkExperience;
use Illuminate\Contracts\View\View;

class HomeController
{
    public function __invoke(): View
    {
        return view('home', [
            'profile' => Profile::find(1),
            'socialLinks' => SocialLink::visible()->orderBy('sort_order')->orderBy('id')->get(),
            'skillCategories' => SkillCategory::visible()
                ->whereHas('skills', fn ($query) => $query->visible())
                ->with(['skills' => fn ($query) => $query->visible()->orderBy('sort_order')->orderBy('id')])
                ->orderBy('sort_order')->orderBy('id')->get(),
            'workExperiences' => WorkExperience::visible()->orderBy('sort_order')->orderByDesc('start_date')->orderBy('id')->get(),
            'educations' => Education::visible()->orderBy('sort_order')->orderByDesc('start_date')->orderBy('id')->get(),
            'featuredProjects' => Project::published()->where('is_featured', true)
                ->with(['category', 'technologies', 'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
                ->orderBy('sort_order')->orderByDesc('published_at')->orderBy('id')->limit(3)->get(),
            'latestPosts' => BlogPost::published()->with('category')->orderByDesc('published_at')->orderByDesc('id')->limit(3)->get(),
            'resumes' => ResumeFile::whereIn('language', ['en', 'fa'])->get()->filter(fn (ResumeFile $resume): bool => $resume->isAvailable())->sortBy('language'),
        ]);
    }
}
