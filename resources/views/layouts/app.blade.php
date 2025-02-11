<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __fallback('app.description') }}">
    <meta name="keywords" content="{{ __fallback('app.keywords') }}">
    <meta name="author" content="Gema Digital">
    <meta name="mobile-web-app-capable" content="yes">

    <title>{{ config('app.name') }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#FFF">
    <meta name="msapplication-TileImage" content="/img/icons/ms-icon-144x144.png">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="theme-color" content="#FFF">

    <meta property="og:url" content="{{ config('app.url') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ __fallback('app.description') }}"/>
    <meta property="og:image" content="{{ config('app.url') }}/img/logo.png"/>

    <link rel="preload" href="{{ url(mix('js/app.js')) }}" as="script"/>
    <link rel="preload" href="{{ url(mix('css/app.css')) }}" as="style"/>
    @yield('preload')

    @if(config('services.google.analytics'))
    <link rel="preconnect" href="https://www.google-analytics.com"/>
    <link rel="preconnect" href="https://www.googletagmanager.com"/>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
    @yield('style')
</head>
<body>
    @include('layouts.navbar')

    <section id="content">
        @yield('content')
    </section>

    @include('layouts.footer')

    <script>
        window.Laravel = {token: '{{ csrf_token() }}'};
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('script')

    @if(config('services.google.analytics'))
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-{{ config('services.google.analytics') }}" max-age=604800></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-{{ config('services.google.analytics') }}');
    </script>
    @endif
</body>
</html>
