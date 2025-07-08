<!DOCTYPE html>
<html lang="{{ current_lang() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ $titleMod['prefix'] ?? '' }}{{ app('seo')->title() }}{{ $titleMod['suffix'] ?? '' }}</title>
    <meta name="description"
        content="{{ $descriptionMod['prefix'] ?? '' }}{{ app('seo')->description() }}{{ $descriptionMod['suffix'] ?? '' }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="SmartCMS">
    <link rel="robots" href="{{ route('robots') }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}">
    @stack('fonts')
    @foreach ($meta_tags as $tag)
        <meta name="{{ $tag['name'] }}" content="{{ $tag['meta_tags'] }}">
    @endforeach
    <meta property="og:type" content="{{ $og_type }}" />
    <meta property="og:title"
        content="{{ $titleMod['prefix'] ?? '' }}{{ app('seo')->title() }}{{ $titleMod['suffix'] ?? '' }}" />
    <meta property="og:description"
        content="{{ $descriptionMod['prefix'] ?? '' }}{{ app('seo')->description() }}{{ $descriptionMod['suffix'] ?? '' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ app('seo')->image() }}" />
    <meta property="og:site_name" content="{{ company_name() }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="{{ '@' . company_name() }}">
    <meta name="twitter:description"
        content="{{ $descriptionMod['prefix'] ?? '' }}{{ app('seo')->description() }}{{ $descriptionMod['suffix'] ?? '' }}">
    <meta name="twitter:title"
        content="{{ $titleMod['prefix'] ?? '' }}{{ app('seo')->title() }}{{ $titleMod['suffix'] ?? '' }}">
    <meta name="twitter:image" content="{{ app('seo')->image() }}">
    <x-kit-theme />
    {!! app('microdata')->render() !!}
    @vite(app('assets')->getCss())
    @vite(app('assets')->getJs())
    @stack('styles')
</head>

<body class="antialiased">
    <x-kit-header />
    <main>
        @yield('content')
    </main>
    <x-kit-footer />
    <x-kit-gtm />
    @foreach ($scripts as $script)
        {!! $script['scripts'] !!}
    @endforeach
</body>

</html>
