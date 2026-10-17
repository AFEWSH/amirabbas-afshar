<x-layouts.public :title="$post->seo_title ?: $post->title" :description="$post->seo_description ?: $post->excerpt" :canonical="route('blog.show', $post->slug)" :image="$post->featured_image_path && \Illuminate\Support\Facades\Storage::disk('blog_images')->exists($post->featured_image_path) ? route('blog-images.show', $post) : null" type="article">
    <article class="site-container py-20 sm:py-28">
        <a class="text-sm font-medium text-accent hover:underline" href="{{ route('blog.index') }}">← All articles</a>
        <div class="mx-auto max-w-3xl">
            <p class="mt-14 text-xs font-semibold uppercase tracking-[.2em] text-accent">{{ $post->category?->name ?: 'Article' }} · <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M j, Y') }}</time></p>
            <h1 class="mt-4 text-5xl font-semibold tracking-tight sm:text-6xl">{{ $post->title }}<span class="text-accent">.</span></h1>
            <p class="mt-7 text-xl leading-8 text-muted">{{ $post->excerpt }}</p>
        </div>
        @if($post->featured_image_path)
            <figure class="mx-auto mt-12 max-w-5xl"><img class="w-full rounded-xl border border-line object-cover" src="{{ route('blog-images.show', $post) }}" alt="{{ $post->featured_image_alt ?? '' }}"></figure>
        @endif
        <div class="article-content mx-auto mt-14 max-w-3xl border-t border-line pt-9">{{ \Filament\Forms\Components\RichEditor\RichContentRenderer::make($post->content) }}</div>
    </article>
</x-layouts.public>
