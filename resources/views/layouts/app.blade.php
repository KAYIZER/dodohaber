<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
/**
 * @var array $theme
 * @var array $site
 * @var array $seo
 * @var \Illuminate\Database\Eloquent\Collection $headerCategories
 */
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $seo['meta_description'] ?? 'Güncel haberler' }}">
    <title>{{ $seo['meta_title'] ?? $site['site_name'] ?? 'Haber Portalı' }}</title>

    <!-- Tailwind & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @php
        $font = $theme['font_family'] ?? 'Inter';
        $fontFamily = str_replace(' ', '+', $font);
    @endphp
    <link href="https://fonts.googleapis.com/css2?family={{ $fontFamily }}:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- CSS Variables for Dynamic Theme Colors -->
    @php
        // Güvenlik: Renk değerlerini hex formatıyla sınırla (CSS injection koruması)
        $colorPrimary = $theme['color_primary'] ?? '#1a56db';
        $colorSecondary = $theme['color_secondary'] ?? '#7c3aed';
        if (!preg_match('/^#[0-9a-fA-F]{3,8}$/', $colorPrimary)) $colorPrimary = '#1a56db';
        if (!preg_match('/^#[0-9a-fA-F]{3,8}$/', $colorSecondary)) $colorSecondary = '#7c3aed';

        // Güvenlik: Font adını alfanumerik + boşluk ile sınırla
        $safeFont = preg_replace('/[^a-zA-Z0-9\s]/', '', $font);
        if (empty($safeFont)) $safeFont = 'Inter';
    @endphp
    <style>
        :root {
            --color-primary: {{ $colorPrimary }};
            --color-secondary: {{ $colorSecondary }};
            --font-family: '{{ $safeFont }}', sans-serif;
        }
        body { font-family: var(--font-family); }
        /* Prevent flicker before Alpine initializes */
        [x-cloak] { display: none !important; }
    </style>
    
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen selection:bg-primary selection:text-white" x-data="{ mobileMenuOpen: false }">
    
    <!-- Dynamic Header Component -->
    @php
        $allowedHeaders = ['type-1', 'type-2', 'type-3'];
        $headerType = $theme['header_type'] ?? 'type-1';
        if (!in_array($headerType, $allowedHeaders)) $headerType = 'type-1';
    @endphp
    @includeIf("components.headers.{$headerType}")

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Dynamic Footer Component -->
    @php
        $allowedFooters = ['type-1', 'type-2', 'type-3'];
        $footerType = $theme['footer_type'] ?? 'type-1';
        if (!in_array($footerType, $allowedFooters)) $footerType = 'type-1';
    @endphp
    @includeIf("components.footers.{$footerType}")

    @livewireScripts
</body>
</html>
