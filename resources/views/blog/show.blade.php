<x-layouts.public :title="$post->publicText('seo_title') ?: $post->publicText('title')" :description="$post->publicText('seo_description') ?: $post->publicText('excerpt')" :canonical="\App\Support\PublicUrl::route('blog.show', $post->slug)" :image="$post->featured_image_path && \Illuminate\Support\Facades\Storage::disk('blog_images')->exists($post->featured_image_path) ? route('blog-images.show', $post) : null" type="article">
    <article class="site-container inner-page article-detail">
        <a class="listing-back-link" href="{{ \App\Support\PublicUrl::route('blog.index') }}"><span aria-hidden="true">{{ app()->getLocale() === 'fa' ? '→' : '←' }}</span> {{ __('All articles') }}</a>
        <header class="article-detail-hero">
            <div class="article-detail-copy">
                <p class="inner-eyebrow"><span></span>{{ $post->category?->publicText('name') ?: __('Article') }}</p>
                <h1>{{ $post->publicText('title') }}<span>.</span></h1>
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}</time>
                <p class="detail-lead">{{ $post->publicText('excerpt') }}</p>
            </div>
            <div class="article-detail-mark" aria-hidden="true">✎</div>
        </header>
        @if($post->featured_image_path)
            <figure class="article-detail-image"><img src="{{ route('blog-images.show', $post) }}" alt="{{ $post->publicText('featured_image_alt') ?: $post->publicText('title') }}" width="1200" height="675" loading="eager" decoding="async"></figure>
        @endif
        <div class="article-content article-body-card">{{ \Filament\Forms\Components\RichEditor\RichContentRenderer::make($post->publicText('content')) }}</div>
    </article>
</x-layouts.public>
