<x-layouts.public :title="$projects->currentPage() === 1 ? 'Projects' : 'Projects — Page '.$projects->currentPage()" :description="$projects->currentPage() === 1 ? 'Browse published projects and selected work.' : 'Browse published projects and selected work. Page '.$projects->currentPage().'.'" :canonical="$projects->currentPage() === 1 ? route('projects.index') : $projects->url($projects->currentPage())">
    <section class="site-container py-20 sm:py-28">
        <p class="mb-4 text-xs font-semibold uppercase tracking-[.2em] text-accent">Portfolio / Projects</p>
        <h1 class="text-5xl font-semibold tracking-tight sm:text-6xl">Projects<span class="text-accent">.</span></h1>
        <p class="mt-6 max-w-2xl text-lg leading-8 text-muted">Selected published work.</p>
        @if($projects->isEmpty())
            <div class="mt-14 rounded-2xl border border-dashed border-line bg-surface p-8 text-muted">No published projects yet.</div>
        @else
            <div class="mt-14 grid gap-5 md:grid-cols-2">@foreach($projects as $project)<x-project-card :project="$project" />@endforeach</div>
            <div class="mt-10">{{ $projects->links() }}</div>
        @endif
    </section>
</x-layouts.public>
