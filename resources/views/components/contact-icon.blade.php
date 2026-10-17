@props(['url' => '', 'icon' => null])
@php
    $host = strtolower(parse_url($url, PHP_URL_HOST) ?: '');
    $networks = ['github' => ['github.com'], 'linkedin' => ['linkedin.com'], 'instagram' => ['instagram.com'], 'telegram' => ['t.me', 'telegram.me'], 'whatsapp' => ['wa.me', 'whatsapp.com'], 'x' => ['x.com', 'twitter.com'], 'youtube' => ['youtube.com', 'youtu.be']];
    $network = $icon;
    if (!filled($network)) {
        foreach ($networks as $key => $domains) {
            foreach ($domains as $domain) {
                if ($host === $domain || str_ends_with($host, '.'.$domain)) { $network = $key; break 2; }
            }
        }
    }
@endphp
<svg {{ $attributes->class('size-5') }} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
    @if($network === 'github')
        <path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.86c-2.78.6-3.37-1.18-3.37-1.18-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.89 1.53 2.34 1.09 2.91.83.09-.65.35-1.09.64-1.34-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.99 1.03-2.69-.1-.25-.45-1.27.1-2.65 0 0 .84-.27 2.75 1.03a9.6 9.6 0 0 1 5 0c1.91-1.3 2.75-1.03 2.75-1.03.55 1.38.2 2.4.1 2.65.64.7 1.03 1.6 1.03 2.69 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.75c0 .27.18.58.69.48A10 10 0 0 0 12 2Z"/>
    @elseif($network === 'linkedin')
        <path d="M5 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM3.5 9h3v12h-3V9Zm5 0h3v1.64A4.1 4.1 0 0 1 15 8.8c3.3 0 4.5 2.1 4.5 5.2v7h-3v-6.3c0-1.8-.3-3.1-2-3.1s-3 1.2-3 3.1V21h-3V9Z"/>
    @elseif($network === 'instagram')
        <rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2"/>
    @elseif($network === 'telegram')
        <path d="m21 3-3.5 18-5.6-4.3-3 2.8.5-5L18 6.8 7.3 13.5 2 11.7 21 3Z"/>
    @elseif($network === 'whatsapp')
        <path d="M20 12a8 8 0 0 1-11.8 7L3 21l1.6-5A8 8 0 1 1 20 12Z" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="m8.3 7.5 1.5 2.8-1 1c.8 1.7 1.7 2.6 3.5 3.4l1-1 2.8 1.5c-.1 2-1.4 2.5-3 2-3.7-1.2-6.1-3.7-7-6.9-.4-1.5.2-2.6 2.2-2.8Z"/>
    @elseif($network === 'x')
        <path d="M4 3h4.7l4.4 6.2L18.5 3H21l-6.8 8L22 21h-4.7l-5.1-7.1L6 21H3.4l7.6-8.9L4 3Zm3.3 2 11 14h1L8.3 5h-1Z"/>
    @elseif($network === 'youtube')
        <rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 6 3-6 3V9Z" fill="var(--color-cyan-950)"/>
    @elseif(str_starts_with($url, 'mailto:'))
        <path d="M3 5h18v14H3V5Zm2 2v.5l7 5 7-5V7l-7 5-7-5Z"/>
    @else
        <path d="M14 3h7v7h-2V6.4l-9.3 9.3-1.4-1.4L17.6 5H14V3ZM3 5h7v2H5v12h12v-5h2v7H3V5Z"/>
    @endif
</svg>
