<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <meta name="theme-color" content="#0A1628">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Ocean Realtors') }}</title>

        @if (request()->is('/'))
            <title>Real Estate in Gurgaon | Rent Property in Gurgaon| OceanRealtors.co.in</title>
            <meta name="description" content="Real Estate Gurgaon - Browse best properties for rent in Gurgaon - View ✓Top Localities. ✓Bachelor Friendly Properties. ✓Owners Listings. Visit Now!">
            <link rel="preload" as="image" href="{{ asset('images/hero/hero-mobile.webp') }}" type="image/webp" media="(max-width: 768px)" fetchpriority="high">
            <link rel="preload" as="image" href="{{ asset('images/hero/hero-desktop.webp') }}" type="image/webp" media="(min-width: 769px)" fetchpriority="high">
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
            media="print"
            onload="this.media='all'"
        >
        <noscript>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
        </noscript>

        <style>
            body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        </style>

        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        @stack('head')
    </head>
    <body class="font-sans antialiased text-primary selection:bg-accent selection:text-white overflow-x-hidden">
        @inertia
    </body>
</html>
