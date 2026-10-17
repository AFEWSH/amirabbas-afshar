{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('home') }}</loc></url>
    <url><loc>{{ route('projects.index') }}</loc></url>
    <url><loc>{{ route('blog.index') }}</loc></url>
    @foreach($projects as $project)
        <url><loc>{{ route('projects.show', $project->slug) }}</loc><lastmod>{{ $project->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
    @foreach($posts as $post)
        <url><loc>{{ route('blog.show', $post->slug) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
</urlset>
