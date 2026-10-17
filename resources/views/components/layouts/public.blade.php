@props(['profile' => null, 'settings' => null, 'title' => 'Home', 'description' => null, 'canonical' => null, 'image' => null, 'type' => 'website'])
@php
    $siteName = $settings?->publicText('site_name') ?: ($profile?->publicText('full_name') ?: (app()->getLocale() === 'fa' ? __('Portfolio') : 'Amir Abbas Afshar'));
    $pageTitle = $title.' · '.$siteName;
    $pageDescription = \Illuminate\Support\Str::limit(strip_tags($description ?: ($settings?->publicText('default_seo_description') ?: __('Portfolio of Amir Abbas Afshar, a full-stack developer specializing in PHP and Laravel.'))), 160, '…');
    $canonicalUrl = $canonical ?: url()->current();
    $languageAlternates = \App\Support\PublicUrl::alternates();
    $sharingImage = $image ?: (($settings?->og_image_path && \Illuminate\Support\Facades\Storage::disk('site_assets')->exists($settings->og_image_path) && in_array(\Illuminate\Support\Facades\Storage::disk('site_assets')->mimeType($settings->og_image_path), ['image/jpeg', 'image/png', 'image/webp'], true)) ? route('site.sharing-image') : null);
@endphp
<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111719">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @foreach($languageAlternates as $locale => $url)<link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">@endforeach
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
    <a href="#main" class="skip-link">{{ __('Skip to content') }}</a>
    <header class="site-header sticky top-0 z-50">
        <nav class="site-container site-header-inner" aria-label="{{ __('Main navigation') }}">
            <a href="{{ \App\Support\PublicUrl::route('home') }}" class="site-brand">
                <img src="{{ asset('images/logo.png') }}" alt="{{ $siteName }}" width="1774" height="887" class="h-16 w-32 object-contain sm:h-20 sm:w-40">
            </a>
            <div class="desktop-nav">
                <a class="nav-link" href="{{ \App\Support\PublicUrl::route('home') }}#about">{{ __('About') }}</a>
                <a class="nav-link" href="{{ \App\Support\PublicUrl::route('home') }}#skills">{{ __('Skills') }}</a>
                <a class="nav-link" href="{{ \App\Support\PublicUrl::route('home') }}#experience">{{ __('Experience') }}</a>
                <a class="nav-link" href="{{ \App\Support\PublicUrl::route('projects.index') }}" @if(request()->routeIs('projects.*', 'en.projects.*')) aria-current="page" @endif>{{ __('Projects') }}</a>
                <a class="nav-link" href="{{ \App\Support\PublicUrl::route('blog.index') }}" @if(request()->routeIs('blog.*', 'en.blog.*')) aria-current="page" @endif>{{ __('Articles') }}</a>
                <a class="nav-link" href="{{ \App\Support\PublicUrl::route('home') }}#contact">{{ __('Contact') }}</a>
            </div>
            <div class="header-actions">
                <a class="header-contact" href="{{ \App\Support\PublicUrl::route('home') }}#contact">{{ __('Get in touch') }}</a>
                <a class="language-link hidden md:inline-flex" href="{{ \App\Support\PublicUrl::alternate() }}" lang="{{ app()->getLocale() === 'fa' ? 'en' : 'fa' }}" hreflang="{{ app()->getLocale() === 'fa' ? 'en' : 'fa' }}">{{ app()->getLocale() === 'fa' ? 'EN' : 'فا' }}</a>
                <details class="mobile-menu relative">
                    <summary>{{ __('Menu') }}</summary>
                    <div class="mobile-menu-panel">
                    <a href="{{ \App\Support\PublicUrl::route('home') }}#about">{{ __('About') }}</a>
                    <a href="{{ \App\Support\PublicUrl::route('home') }}#skills">{{ __('Skills') }}</a>
                    <a href="{{ \App\Support\PublicUrl::route('home') }}#experience">{{ __('Experience') }}</a>
                    <a href="{{ \App\Support\PublicUrl::route('projects.index') }}">{{ __('Projects') }}</a>
                    <a href="{{ \App\Support\PublicUrl::route('blog.index') }}">{{ __('Articles') }}</a>
                    <a href="{{ \App\Support\PublicUrl::route('home') }}#contact">{{ __('Contact') }}</a>
                    <a href="{{ \App\Support\PublicUrl::alternate() }}" lang="{{ app()->getLocale() === 'fa' ? 'en' : 'fa' }}" hreflang="{{ app()->getLocale() === 'fa' ? 'en' : 'fa' }}">{{ app()->getLocale() === 'fa' ? 'English' : 'فارسی' }}</a>
                    </div>
                </details>
            </div>
        </nav>
    </header>
    <main id="main">{{ $slot }}</main>
    <footer class="site-footer py-10 sm:py-14">
        <div class="site-container grid gap-8 sm:grid-cols-3 sm:items-start">
            <div>
                <a href="{{ \App\Support\PublicUrl::route('home') }}" class="site-brand inline-flex">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ $siteName }}" width="1774" height="887" class="h-20 w-40 object-contain" loading="lazy">
                </a>
                <p class="mt-3 max-w-xs text-sm leading-6 text-muted">{{ $profile?->publicText('title') ?: __('Full-Stack Developer') }}</p>
            </div>
            <nav class="flex flex-col gap-3" aria-label="{{ __('Footer navigation') }}">
                <a class="footer-link" href="{{ \App\Support\PublicUrl::route('home') }}#about">{{ __('About') }}</a>
                <a class="footer-link" href="{{ \App\Support\PublicUrl::route('projects.index') }}">{{ __('Projects') }}</a>
                <a class="footer-link" href="{{ \App\Support\PublicUrl::route('blog.index') }}">{{ __('Articles') }}</a>
                <a class="footer-link" href="{{ \App\Support\PublicUrl::route('home') }}#contact">{{ __('Contact') }}</a>
            </nav>
            <div class="sm:text-end">
                <a class="button-secondary" href="#main">{{ __('Back to top') }} <span aria-hidden="true">↑</span></a>
                <p class="mt-5 text-xs text-muted">© {{ date('Y') }} {{ $siteName }}</p>
            </div>
        </div>
    </footer>
</body>
</html>
