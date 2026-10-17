<x-layouts.public :title="$project->seo_title ?: $project->title" :description="$project->seo_description ?: $project->summary" :canonical="route('projects.show', $project->slug)" :image="$project->images->first() && \Illuminate\Support\Facades\Storage::disk('project_images')->exists($project->images->first()->path) ? route('project-images.show', $project->images->first()) : null">
    <article class="site-container py-20 sm:py-28">
        <a class="text-sm font-medium text-accent hover:underline" href="{{ route('projects.index') }}">← All projects</a>
        @if($project->category)<p class="mt-14 text-xs font-semibold uppercase tracking-[.2em] text-accent">{{ $project->category->name }}</p>@endif
        <h1 class="mt-4 text-5xl font-semibold tracking-tight sm:text-6xl">{{ $project->title }}<span class="text-accent">.</span></h1>
        <p class="mt-7 max-w-3xl text-xl leading-8 text-muted">{{ $project->summary }}</p>
        <div class="mt-8 flex flex-wrap gap-3">
            @if($project->github_url && preg_match('/^https?:\/\//i', $project->github_url))<a class="button-secondary" href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer">GitHub ↗</a>@endif
            @if($project->live_url && preg_match('/^https?:\/\//i', $project->live_url))<a class="button-secondary" href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer">Live site ↗</a>@endif
        </div>
        @if($project->technologies->isNotEmpty())
            <div class="mt-10 flex flex-wrap gap-2" aria-label="Technologies">
                @foreach($project->technologies as $technology)<span class="rounded-full border border-line px-3 py-1 text-sm text-muted">{{ $technology->name }}</span>@endforeach
            </div>
        @endif
        @if($project->images->isNotEmpty())
            <div class="mt-14 grid gap-6">
                @foreach($project->images as $image)<figure><img class="w-full rounded-xl border border-line object-cover" src="{{ route('project-images.show', $image) }}" alt="{{ $image->alt_text }}" loading="lazy"></figure>@endforeach
            </div>
        @endif
        <div class="mt-14 max-w-3xl space-y-10 border-t border-line pt-9 leading-8 text-muted">
            @foreach(['body' => 'Overview', 'role' => 'My role', 'challenges' => 'Challenges', 'solutions' => 'Solutions'] as $field => $heading)
                @if($project->$field)<section><h2 class="text-2xl font-semibold text-paper">{{ $heading }}</h2><p class="mt-4 whitespace-pre-line">{{ $project->$field }}</p></section>@endif
            @endforeach
        </div>
    </article>
</x-layouts.public>
