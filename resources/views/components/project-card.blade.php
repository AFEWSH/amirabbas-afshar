@props(['project', 'variant' => 'default'])
<article class="relative overflow-hidden rounded-2xl border border-line bg-surface transition hover:border-accent/60 {{ $variant === 'featured' ? 'home-featured-project' : '' }}">
    @if($project->images->isNotEmpty())
        <img class="aspect-video w-full object-cover" src="{{ route('project-images.show', $project->images->first()) }}" alt="{{ $project->images->first()->publicText('alt_text') ?: $project->publicText('title') }}" width="1200" height="675" loading="lazy" decoding="async">
    @endif
    <div class="p-6 sm:p-7">
        @if($project->category?->publicText('name'))<p class="text-xs font-semibold uppercase tracking-[.16em] text-accent">{{ $project->category->publicText('name') }}</p>@endif
        <h3 class="mt-3 text-xl font-semibold text-paper"><a class="after:absolute after:inset-0" href="{{ \App\Support\PublicUrl::route('projects.show', $project->slug) }}">{{ $project->publicText('title') }}</a></h3>
        <p class="mt-3 leading-7 text-muted">{{ $project->publicText('summary') }}</p>
        @if($project->technologies->contains(fn ($technology) => $technology->publicText('name')))<p class="mt-5 text-sm text-muted">{{ $project->technologies->map(fn ($technology) => $technology->publicText('name'))->filter()->join(' · ') }}</p>@endif
        <span aria-hidden="true" class="mt-6 inline-block text-sm font-medium text-accent">{{ __('View project') }} ↗</span>
    </div>
</article>
