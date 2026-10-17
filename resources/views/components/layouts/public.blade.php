@props(['profile' => null, 'settings' => null, 'title' => 'Home', 'description' => null, 'canonical' => null, 'image' => null, 'type' => 'website'])
@php
    $siteName = $settings?->site_name ?: ($profile?->full_name ?: 'Amir Abbas Afshar');
    $pageTitle = $title.' · '.$siteName;
    $pageDescription = \Illuminate\Support\Str::limit(strip_tags($description ?: ($settings?->default_seo_description ?: 'Portfolio of Amir Abbas Afshar, a full-stack developer specializing in PHP and Laravel.')), 160, '…');
    $canonicalUrl = $canonical ?: url()->current();
    $sharingImage = $image ?: (($settings?->og_image_path && \Illuminate\Support\Facades\Storage::disk('site_assets')->exists($settings->og_image_path) && in_array(\Illuminate\Support\Facades\Storage::disk('site_assets')->mimeType($settings->og_image_path), ['image/jpeg', 'image/png', 'image/webp'], true)) ? route('site.sharing-image') : null);
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101719">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    @if($sharingImage)<meta property="og:image" content="{{ $sharingImage }}">@endif
    <meta name="twitter:card" content="{{ $sharingImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if($sharingImage)<meta name="twitter:image" content="{{ $sharingImage }}">@endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ink text-paper antialiased">
    <a href="#main" class="skip-link">Skip to content</a>
    <header class="sticky top-0 z-50 border-b border-line bg-ink/95 backdrop-blur">
        <nav class="site-container flex min-h-18 items-center justify-between gap-6" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-paper">{{ $siteName }}<span class="text-accent">.</span></a>
            <div class="hidden items-center gap-7 text-sm text-muted md:flex">
                <a class="nav-link" href="{{ route('home') }}#about">About</a>
                <a class="nav-link" href="{{ route('home') }}#skills">Skills</a>
                <a class="nav-link" href="{{ route('home') }}#experience">Experience</a>
                <a class="nav-link" href="{{ route('projects.index') }}" @if(request()->routeIs('projects.*')) aria-current="page" @endif>Projects</a>
                <a class="nav-link" href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Articles</a>
                <a class="nav-link" href="{{ route('home') }}#contact">Contact</a>
            </div>
            <details class="mobile-menu relative md:hidden">
                <summary class="cursor-pointer rounded-md border border-line px-3 py-2 text-sm font-medium focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">Menu</summary>
                <div class="absolute end-0 top-full mt-3 w-48 rounded-xl border border-line bg-surface p-2 shadow-xl">
                    <a href="{{ route('home') }}#about">About</a>
                    <a href="{{ route('home') }}#skills">Skills</a>
                    <a href="{{ route('home') }}#experience">Experience</a>
                    <a href="{{ route('projects.index') }}">Projects</a>
                    <a href="{{ route('blog.index') }}">Articles</a>
                    <a href="{{ route('home') }}#contact">Contact</a>
                </div>
            </details>
        </nav>
    </header>
    <main id="main">{{ $slot }}</main>
    <footer class="border-t border-line py-10">
        <div class="site-container flex flex-col justify-between gap-3 text-sm text-muted sm:flex-row">
            <p>© {{ date('Y') }} {{ $profile?->full_name ?: 'Amir Abbas Afshar' }} · {{ $profile?->title ?: 'Full-Stack Developer' }}</p>
            <a class="text-accent hover:underline" href="{{ route('home') }}#contact">Contact</a>
        </div>
    </footer>
</body>
</html>
