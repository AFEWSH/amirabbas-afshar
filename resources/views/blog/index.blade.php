<x-layouts.public :title="$posts->currentPage() === 1 ? 'Articles' : 'Articles — Page '.$posts->currentPage()" :description="$posts->currentPage() === 1 ? 'Read published articles about development and software engineering.' : 'Read published articles about development and software engineering. Page '.$posts->currentPage().'.'" :canonical="$posts->currentPage() === 1 ? route('blog.index') : $posts->url($posts->currentPage())">
    <section class="site-container py-20 sm:py-28">
        <p class="mb-4 text-xs font-semibold uppercase tracking-[.2em] text-accent">Journal / Articles</p>
        <h1 class="text-5xl font-semibold tracking-tight sm:text-6xl">Articles<span class="text-accent">.</span></h1>
        <p class="mt-6 max-w-2xl text-lg leading-8 text-muted">Published notes and ideas.</p>
        @if($posts->isEmpty())
            <div class="mt-14 rounded-2xl border border-dashed border-line bg-surface p-8 text-muted">No articles have been published yet.</div>
        @else
            <div class="mt-14 grid gap-5 md:grid-cols-2">@foreach($posts as $post)<x-article-card :post="$post" />@endforeach</div>
            <div class="mt-10">{{ $posts->links() }}</div>
        @endif
    </section>
</x-layouts.public>
