@props(['post'])
<article class="relative overflow-hidden rounded-2xl border border-line bg-surface transition hover:border-accent/60">
    @if($post->featured_image_path)
        <img class="aspect-video w-full object-cover" src="{{ route('blog-images.show', $post) }}" alt="{{ $post->featured_image_alt ?? '' }}" width="1200" height="675" loading="lazy" decoding="async">
    @endif
    <div class="p-7">
        <p class="text-xs font-semibold uppercase tracking-[.16em] text-accent">{{ $post->category?->name ?: 'Article' }} · {{ $post->published_at->format('M j, Y') }}</p>
        <h3 class="mt-3 text-xl font-semibold text-paper"><a class="after:absolute after:inset-0" href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
        <p class="mt-3 leading-7 text-muted">{{ $post->excerpt }}</p>
        <span aria-hidden="true" class="mt-6 inline-block text-sm font-medium text-accent">Read article ↗</span>
    </div>
</article>
