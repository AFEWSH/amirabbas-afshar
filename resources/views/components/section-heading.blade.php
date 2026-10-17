@props(['eyebrow', 'title', 'description' => null])
<div class="mb-9 max-w-2xl">
    <p class="mb-3 text-xs font-semibold uppercase tracking-[.2em] text-accent">{{ $eyebrow }}</p>
    <h2 class="text-3xl font-semibold tracking-tight text-paper sm:text-4xl">{{ $title }}</h2>
    @if($description)<p class="mt-4 leading-7 text-muted">{{ $description }}</p>@endif
</div>
