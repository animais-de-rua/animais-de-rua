@if(Request::ajax() || Request::has('ajax'))
    <script>document.body || window.location.reload()</script>
    @yield('content')
@else
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('description') }}">
    <meta name="keywords" content="{{ __('keywords') }}">
    <meta name="author" content="promatik.pt">
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
    {{-- <link rel="icon" type="image/png" sizes="96x96" href="/img/icons/favicon-96x96.png"> --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#E53625">
    <meta name="msapplication-TileImage" content="/img/icons/ms-icon-144x144.png">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="theme-color" content="#E53625">

    <meta property="og:url" content="{{ env('APP_URL') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="fb:admins" content="100000258524385">
@if (trim($__env->yieldContent('facebook')))
@yield('facebook')
@else
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ __('description') }}"/>
    <meta property="og:image" content="{{ env('APP_URL') }}/img/logo/facebook.png"/>
@endif

    <link rel="preload" href="{{ mix('js/app.js') }}" as="script"/>
    <link rel="preload" href="{{ mix('css/app.css') }}" as="style"/>
    <link rel="preload" href="{{ '/img/logo/logo.svg' }}" as="image"/>
    <link rel="preload" href="{{ "/fonts/icomoon.woff2?58c2d240bb8f452ceba24cac9e697d8e" }}" as="font" type="font/woff2" crossorigin="anonymous"/>

    <link rel="preconnect" href="https://www.google-analytics.com"/>
    <link rel="preconnect" href="https://www.googletagmanager.com"/>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />

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

    @include('cookieConsent::index')

    <script>
        window.Laravel = {token: '{{ csrf_token() }}'};
        window.translations = {month: [
            '{{ __('January') }}', '{{ __('February') }}', '{{ __('March') }}', '{{ __('April') }}',
            '{{ __('May') }}', '{{ __('June') }}', '{{ __('July') }}', '{{ __('August') }}',
            '{{ __('September') }}', '{{ __('October') }}', '{{ __('November') }}', '{{ __('December') }}']};
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
    {{-- <script>
        if(typeof onDocumentReady === 'function')
            document.addEventListener("DOMContentLoaded", onDocumentReady);
    </script> --}}
    @yield('script')

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118312120-1" max-age=604800></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-118312120-1');
    </script>
</body>
</html>
@endif
