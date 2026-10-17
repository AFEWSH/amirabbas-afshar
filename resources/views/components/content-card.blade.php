@props(['label', 'title', 'description', 'href'])
<article class="relative flex h-full flex-col rounded-2xl border border-line bg-surface p-7 transition hover:border-accent/60">
    <p class="text-xs font-semibold uppercase tracking-[.16em] text-accent">{{ $label }}</p>
    <h3 class="mt-5 text-xl font-semibold text-paper"><a class="after:absolute after:inset-0 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent" href="{{ $href }}">{{ $title }}</a></h3>
    <p class="mt-3 flex-1 leading-7 text-muted">{{ $description }}</p>
    <span aria-hidden="true" class="mt-7 text-sm font-medium text-accent">Explore <span>↗</span></span>
</article>
