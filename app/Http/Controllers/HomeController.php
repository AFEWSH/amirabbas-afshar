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
            'socialLinks' => SocialLink::visible()->availableInPublicLocale('label')->orderBy('sort_order')->orderBy('id')->get(),
            'skillCategories' => SkillCategory::visible()
                ->availableInPublicLocale('name')
                ->whereHas('skills', fn ($query) => $query->visible()->availableInPublicLocale('name'))
                ->with(['skills' => fn ($query) => $query->visible()->availableInPublicLocale('name')->orderBy('sort_order')->orderBy('id')])
                ->orderBy('sort_order')->orderBy('id')->get(),
            'workExperiences' => WorkExperience::visible()->availableInPublicLocale('company')->availableInPublicLocale('job_title')->orderBy('sort_order')->orderByDesc('start_date')->orderBy('id')->get(),
            'educations' => Education::visible()->availableInPublicLocale('institution')->orderBy('sort_order')->orderByDesc('start_date')->orderBy('id')->get(),
            'featuredProjects' => Project::published()->availableInPublicLocale('title')->availableInPublicLocale('summary')->where('is_featured', true)
                ->with(['category', 'technologies', 'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
                ->orderBy('sort_order')->orderByDesc('published_at')->orderBy('id')->limit(3)->get(),
            'latestPosts' => BlogPost::published()->availableInPublicLocale('title')->availableInPublicLocale('excerpt')->availableInPublicLocale('content')->with('category')->orderByDesc('published_at')->orderByDesc('id')->limit(3)->get(),
            'resumes' => ResumeFile::where('language', app()->getLocale())->get()->filter(fn (ResumeFile $resume): bool => $resume->isAvailable()),
        ]);
    }
}
