@if(Request::ajax() || Request::has('ajax'))
<script>document.body || window.location.reload();</script>
@yield('content')
@else
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ __('description') }}" />
    <meta name="keywords" content="{{ __('keywords') }}" />
    <meta name="author" content="promatik.pt" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="generator" content="Laravel" />

    <title>@hasSection('title')@yield('title')@else{{ config('app.name') }}@endif</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/img/icons/apple-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="60x60" href="/img/icons/apple-icon-60x60.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icons/apple-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/img/icons/apple-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icons/apple-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/img/icons/apple-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icons/apple-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/img/icons/apple-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-icon-180x180.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png" />
    {{-- <link rel="icon" type="image/png" sizes="96x96" href="/img/icons/favicon-96x96.png"> --}}
    <link rel="manifest" href="/manifest.json" />
    <meta name="msapplication-TileColor" content="#E53625" />
    <meta name="msapplication-TileImage" content="/img/icons/ms-icon-144x144.png" />
    <meta name="msapplication-config" content="/browserconfig.xml" />
    <meta name="theme-color" content="#E53625" />

    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="fb:admins" content="100000258524385" />
@if (trim($__env->yieldContent('facebook')))
@yield('facebook')
@else
    <meta property="og:title" content="{{ config('app.name') }}" />
    <meta property="og:description" content="{{ __('description') }}" />
    <meta property="og:image" content="{{ config('app.url') }}img/logo/facebook.png" />
@endif

    <link rel="preload" href="{{ Vite::asset('resources/js/app.js') }}" as="script" />
    <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style" />
    <link rel="preload" href="{{ "/fonts/icomoon.woff2?3f18b6535991cd09292b06b870eb6400" }}" as="font" type="font/woff2" crossorigin="anonymous" />

    <link rel="preconnect" href="https://www.google-analytics.com" />
    <link rel="preconnect" href="https://www.googletagmanager.com" />
    <link rel="preconnect" href="https://stats.g.doubleclick.net" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @vite('resources/css/app.css')

    @yield('style')
</head>
<body>

    @include('layouts.navbar')

    <div class="network">{{ __('You are browsing offline, some features may not work.') }}</div>

    <section id="content">
        @yield('content')
    </section>

    @include('layouts.footer')

    @include('layouts.forms')

    @include('cookie-consent::index')

    <script>
        window.Laravel = {{ Js::from([
            'token' => csrf_token(),
            'title' => config('app.name'),
        ]) }};
        window.translations = {{ Js::from([
            'month' => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
        ]) }};
    </script>
    <script src="{{ Vite::asset('resources/js/app.js') }}"></script>
    @yield('script')

    {{-- Google Tag Manager Code --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118312120-1" max-age=604800></script>
    <script>
        window.dataLayer ||= [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-118312120-1');
    </script>

    {{-- Facebook Pixel Code --}}
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window,document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '2207182759596227');
        fbq('track', 'PageView');
    </script>
</body>
</html>
@endif
