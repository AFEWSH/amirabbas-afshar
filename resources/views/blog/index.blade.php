<x-layouts.public :title="$posts->currentPage() === 1 ? __('Articles') : __('Articles').' — '.__('Page :page', ['page' => $posts->currentPage()])" :description="$posts->currentPage() === 1 ? __('Read published articles about development and software engineering.') : __('Read published articles about development and software engineering.').' '.__('Page :page', ['page' => $posts->currentPage()]).'.'" :canonical="$posts->currentPage() === 1 ? \App\Support\PublicUrl::route('blog.index') : $posts->url($posts->currentPage())">
    <div class="site-container inner-page">
        <header class="listing-hero listing-hero-articles">
            <div class="listing-hero-copy">
                <a class="listing-back-link" href="{{ \App\Support\PublicUrl::route('home') }}"><span aria-hidden="true">{{ app()->getLocale() === 'fa' ? '→' : '←' }}</span> {{ __('Home') }}</a>
                <p class="inner-eyebrow"><span></span>{{ __('Writing') }}</p>
                <h1>{{ __('Articles') }}<span>.</span></h1>
                <p>{{ __('Read published articles about development and software engineering.') }}</p>
            </div>
            <div class="listing-hero-art listing-article-art" aria-hidden="true"><span>✎</span><i></i><i></i></div>
        </header>

        @if($posts->isEmpty())
            <div class="inner-empty"><span class="empty-mark" aria-hidden="true">✳</span><p>{{ __('No articles have been published yet.') }}</p></div>
        @else
            <div class="inner-card-grid article-list-grid">@foreach($posts as $post)<x-article-card :post="$post" />@endforeach</div>
            @if($posts->hasPages())<div class="inner-pagination">{{ $posts->links() }}</div>@endif
        @endif
    </div>
</x-layouts.public>
