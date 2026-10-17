{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach([['home', 'en.home'], ['projects.index', 'en.projects.index'], ['blog.index', 'en.blog.index']] as $routes)
        <url><loc>{{ route($routes[0]) }}</loc><xhtml:link rel="alternate" hreflang="fa-IR" href="{{ route($routes[0]) }}"/><xhtml:link rel="alternate" hreflang="en" href="{{ route($routes[1]) }}"/></url>
        <url><loc>{{ route($routes[1]) }}</loc><xhtml:link rel="alternate" hreflang="fa-IR" href="{{ route($routes[0]) }}"/><xhtml:link rel="alternate" hreflang="en" href="{{ route($routes[1]) }}"/></url>
    @endforeach
    @foreach($projects as $project)
        @if($translatedProjectSlugs->has($project->slug))
            <url><loc>{{ route('projects.show', $project->slug) }}</loc><lastmod>{{ $project->updated_at->toAtomString() }}</lastmod><xhtml:link rel="alternate" hreflang="fa-IR" href="{{ route('projects.show', $project->slug) }}"/><xhtml:link rel="alternate" hreflang="en" href="{{ route('en.projects.show', $project->slug) }}"/></url>
            <url><loc>{{ route('en.projects.show', $project->slug) }}</loc><lastmod>{{ $project->updated_at->toAtomString() }}</lastmod><xhtml:link rel="alternate" hreflang="fa-IR" href="{{ route('projects.show', $project->slug) }}"/><xhtml:link rel="alternate" hreflang="en" href="{{ route('en.projects.show', $project->slug) }}"/></url>
        @else
            <url><loc>{{ route('en.projects.show', $project->slug) }}</loc><lastmod>{{ $project->updated_at->toAtomString() }}</lastmod></url>
        @endif
    @endforeach
    @foreach($posts as $post)
        @if($translatedPostSlugs->has($post->slug))
            <url><loc>{{ route('blog.show', $post->slug) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod><xhtml:link rel="alternate" hreflang="fa-IR" href="{{ route('blog.show', $post->slug) }}"/><xhtml:link rel="alternate" hreflang="en" href="{{ route('en.blog.show', $post->slug) }}"/></url>
            <url><loc>{{ route('en.blog.show', $post->slug) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod><xhtml:link rel="alternate" hreflang="fa-IR" href="{{ route('blog.show', $post->slug) }}"/><xhtml:link rel="alternate" hreflang="en" href="{{ route('en.blog.show', $post->slug) }}"/></url>
        @else
            <url><loc>{{ route('en.blog.show', $post->slug) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod></url>
        @endif
    @endforeach
</urlset>
