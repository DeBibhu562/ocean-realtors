<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <meta name="theme-color" content="#0A1628">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Ocean Realtors') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">

        @if (request()->is('/'))
            <title>Real Estate in Gurgaon | Rent Property in Gurgaon| OceanRealtors.co.in</title>
            <meta name="description" content="Real Estate Gurgaon - Browse best properties for rent in Gurgaon - View ✓Top Localities. ✓Bachelor Friendly Properties. ✓Owners Listings. Visit Now!">
            <link rel="preload" as="image" href="{{ asset('images/hero/hero-mobile.webp') }}" type="image/webp" media="(max-width: 768px)" fetchpriority="high">
            <link rel="preload" as="image" href="{{ asset('images/hero/hero-desktop.webp') }}" type="image/webp" media="(min-width: 769px)" fetchpriority="high">
        @endif

        @if (! empty($lcpPreloadImage))
            <link rel="preload" as="image" href="{{ $lcpPreloadImage }}" type="image/webp" fetchpriority="high">
        @endif

        <link rel="preload" href="{{ asset('fonts/inter/inter-400.woff2') }}" as="font" type="font/woff2" crossorigin>

        <style>
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 400;
                font-display: swap;
                src: url('{{ asset('fonts/inter/inter-400.woff2') }}') format('woff2');
            }
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 500;
                font-display: optional;
                src: url('{{ asset('fonts/inter/inter-500.woff2') }}') format('woff2');
            }
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 600;
                font-display: optional;
                src: url('{{ asset('fonts/inter/inter-600.woff2') }}') format('woff2');
            }
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 700;
                font-display: optional;
                src: url('{{ asset('fonts/inter/inter-700.woff2') }}') format('woff2');
            }
            body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
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
