<x-layouts.public :title="$projects->currentPage() === 1 ? __('Projects') : __('Projects').' — '.__('Page :page', ['page' => $projects->currentPage()])" :description="$projects->currentPage() === 1 ? __('Browse published projects and selected work.') : __('Browse published projects and selected work.').' '.__('Page :page', ['page' => $projects->currentPage()]).'.'" :canonical="$projects->currentPage() === 1 ? \App\Support\PublicUrl::route('projects.index') : $projects->url($projects->currentPage())">
    <div class="site-container inner-page">
        <header class="listing-hero listing-hero-projects">
            <div class="listing-hero-copy">
                <a class="listing-back-link" href="{{ \App\Support\PublicUrl::route('home') }}"><span aria-hidden="true">{{ app()->getLocale() === 'fa' ? '→' : '←' }}</span> {{ __('Home') }}</a>
                <p class="inner-eyebrow"><span></span>{{ __('Selected work') }}</p>
                <h1>{{ __('Projects') }}<span>.</span></h1>
                <p>{{ __('Browse published projects and selected work.') }}</p>
            </div>
            <div class="listing-hero-art listing-project-art" aria-hidden="true"><span>&lt;/&gt;</span><i></i><i></i></div>
        </header>

        @if($projects->isEmpty())
            <div class="inner-empty"><span class="empty-mark" aria-hidden="true">✳</span><p>{{ __('No published projects yet.') }}</p></div>
        @else
            <div class="inner-card-grid project-list-grid">@foreach($projects as $project)<x-project-card :project="$project" />@endforeach</div>
            @if($projects->hasPages())<div class="inner-pagination">{{ $projects->links() }}</div>@endif
        @endif
    </div>
</x-layouts.public>
