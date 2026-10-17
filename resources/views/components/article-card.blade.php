@props(['post', 'variant' => 'default'])
<article class="relative overflow-hidden rounded-2xl border border-line bg-surface transition hover:border-accent/60 {{ $variant === 'featured' ? 'home-featured-article' : '' }}">
    @if($post->featured_image_path)
        <img class="aspect-video w-full object-cover" src="{{ route('blog-images.show', $post) }}" alt="{{ $post->publicText('featured_image_alt') ?: $post->publicText('title') }}" width="1200" height="675" loading="lazy" decoding="async">
    @endif
    <div class="p-5 sm:p-6">
        <p class="text-xs font-semibold uppercase tracking-[.16em] text-accent">{{ $post->category?->publicText('name') ?: __('Article') }} · {{ $post->published_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}</p>
        <h3 class="mt-3 text-xl font-semibold text-paper"><a class="after:absolute after:inset-0" href="{{ \App\Support\PublicUrl::route('blog.show', $post->slug) }}">{{ $post->publicText('title') }}</a></h3>
        <p class="mt-3 leading-7 text-muted">{{ $post->publicText('excerpt') }}</p>
        <span aria-hidden="true" class="mt-6 inline-block text-sm font-medium text-accent">{{ __('Read article') }} ↗</span>
    </div>
</article>
