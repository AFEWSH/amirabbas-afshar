<x-layouts.public :title="$project->publicText('seo_title') ?: $project->publicText('title')" :description="$project->publicText('seo_description') ?: $project->publicText('summary')" :canonical="\App\Support\PublicUrl::route('projects.show', $project->slug)" :image="$project->images->first() && \Illuminate\Support\Facades\Storage::disk('project_images')->exists($project->images->first()->path) ? route('project-images.show', $project->images->first()) : null">
    <article class="site-container inner-page project-detail">
        <div class="detail-topline">
            <a class="listing-back-link" href="{{ \App\Support\PublicUrl::route('projects.index') }}"><span aria-hidden="true">{{ app()->getLocale() === 'fa' ? '→' : '←' }}</span> {{ __('All projects') }}</a>
        </div>

        <header class="detail-hero detail-hero-projects">
            <div class="detail-hero-copy">
                @if($project->category?->publicText('name'))<p class="inner-eyebrow"><span></span>{{ $project->category->publicText('name') }}</p>@endif
                <h1>{{ $project->publicText('title') }}<span>.</span></h1>
                <p class="detail-lead">{{ $project->publicText('summary') }}</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    @if($project->github_url && preg_match('/^https?:\/\//i', $project->github_url))<a class="button-secondary" href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer">{{ __('GitHub') }} ↗</a>@endif
                    @if($project->live_url && preg_match('/^https?:\/\//i', $project->live_url))<a class="button-primary" href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer">{{ __('Live site') }} ↗</a>@endif
                </div>
                @if($project->technologies->contains(fn ($technology) => $technology->publicText('name')))
                    <div class="detail-tags" aria-label="{{ __('Technologies') }}">
                        @foreach($project->technologies as $technology)@if($technology->publicText('name'))<span>{{ $technology->publicText('name') }}</span>@endif @endforeach
                    </div>
                @endif
            </div>
            <div class="detail-hero-mark" aria-hidden="true">&lt;/&gt;</div>
        </header>

        @if($project->images->isNotEmpty())
            <div class="project-gallery">
                @foreach($project->images as $image)
                    <figure class="{{ $loop->first ? 'project-gallery-featured' : '' }}"><img src="{{ route('project-images.show', $image) }}" alt="{{ $image->publicText('alt_text') ?: $project->publicText('title') }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"></figure>
                @endforeach
            </div>
        @endif

        <div class="detail-sections">
            @foreach(['body' => __('Overview'), 'role' => __('My role'), 'challenges' => __('Challenges'), 'solutions' => __('Solutions')] as $field => $heading)
                @if($project->publicText($field))
                    <section class="detail-content-card">
                        <p class="inner-eyebrow"><span></span>{{ $heading }}</p>
                        <h2>{{ $heading }}</h2>
                        <p>{{ $project->publicText($field) }}</p>
                    </section>
                @endif
            @endforeach
        </div>
    </article>
</x-layouts.public>
