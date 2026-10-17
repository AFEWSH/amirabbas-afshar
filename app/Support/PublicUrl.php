<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

class PublicUrl
{
    public static function route(string $name, mixed $parameters = []): string
    {
        return route((app()->getLocale() === 'en' ? 'en.' : '').$name, $parameters);
    }

    public static function alternate(): string
    {
        $current = Route::current();
        $name = $current?->getName() ?? 'home';
        $name = str_starts_with($name, 'en.') ? substr($name, 3) : $name;
        $target = (app()->getLocale() === 'en' ? '' : 'en.').$name;

        if (app()->getLocale() === 'en' && in_array($name, ['projects.show', 'blog.show'], true)) {
            $model = $name === 'projects.show' ? Project::class : BlogPost::class;
            $query = $model::published()->where('slug', $current?->parameter('slug'))
                ->whereNotNull('title_fa')->where('title_fa', '!=', '');
            $query = $name === 'projects.show'
                ? $query->whereNotNull('summary_fa')->where('summary_fa', '!=', '')
                : $query->whereNotNull('excerpt_fa')->where('excerpt_fa', '!=', '')->whereNotNull('content_fa')->where('content_fa', '!=', '');

            if (! $query->exists()) {
                return route($name === 'projects.show' ? 'projects.index' : 'blog.index');
            }
        }

        return route($target, $current?->parameters() ?? []);
    }

    public static function alternates(): array
    {
        $current = Route::current();
        $name = $current?->getName() ?? 'home';
        $baseName = str_starts_with($name, 'en.') ? substr($name, 3) : $name;

        if (! in_array($baseName, ['home', 'projects.index', 'projects.show', 'blog.index', 'blog.show'], true)) {
            return [];
        }

        if (in_array($baseName, ['projects.index', 'blog.index'], true) && request()->integer('page', 1) > 1) {
            return [];
        }

        if (in_array($baseName, ['projects.show', 'blog.show'], true)) {
            $model = $baseName === 'projects.show' ? Project::class : BlogPost::class;
            $query = $model::published()->where('slug', $current?->parameter('slug'));

            if ($baseName === 'projects.show') {
                $query->whereNotNull('summary_fa')->where('summary_fa', '!=', '');
            } else {
                $query->whereNotNull('excerpt_fa')->where('excerpt_fa', '!=', '')
                    ->whereNotNull('content_fa')->where('content_fa', '!=', '');
            }

            if (! $query->whereNotNull('title_fa')->where('title_fa', '!=', '')->exists()) {
                return [];
            }
        }

        $parameters = $current?->parameters() ?? [];

        return [
            'fa-IR' => route($baseName, $parameters),
            'en' => route('en.'.$baseName, $parameters),
        ];
    }
}
