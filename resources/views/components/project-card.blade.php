@props(['project'])
<article class="relative overflow-hidden rounded-2xl border border-line bg-surface transition hover:border-accent/60">
    @if($project->images->isNotEmpty())
        <img class="aspect-video w-full object-cover" src="{{ route('project-images.show', $project->images->first()) }}" alt="{{ $project->images->first()->alt_text }}" width="1200" height="675" loading="lazy" decoding="async">
    @endif
    <div class="p-7">
        @if($project->category)<p class="text-xs font-semibold uppercase tracking-[.16em] text-accent">{{ $project->category->name }}</p>@endif
        <h3 class="mt-3 text-xl font-semibold text-paper"><a class="after:absolute after:inset-0" href="{{ route('projects.show', $project->slug) }}">{{ $project->title }}</a></h3>
        <p class="mt-3 leading-7 text-muted">{{ $project->summary }}</p>
        @if($project->technologies->isNotEmpty())<p class="mt-5 text-sm text-muted">{{ $project->technologies->pluck('name')->join(' · ') }}</p>@endif
        <span aria-hidden="true" class="mt-6 inline-block text-sm font-medium text-accent">View project ↗</span>
    </div>
</article>
