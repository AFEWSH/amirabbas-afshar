<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="mx-auto max-w-3xl px-6 py-24">
        <h1 class="text-3xl font-semibold">Personal Portfolio</h1>
        <p class="mt-4 text-slate-300">Phase 1 foundation is being prepared.</p>
    </main>
</body>
</html>
