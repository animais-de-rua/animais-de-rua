<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('desc') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="refresh" content="3600">

    <title>{{ config('app.name') }}</title>

    <!-- Icons -->
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
    <link rel="icon" type="image/png" sizes="96x96" href="/img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="256x256" href="/img/icons/android-icon-256x256.png">
    <link rel="manifest" href="{{ $manifest_base ?? '' }}/manifest.json">
    <meta name="msapplication-TileColor" content="#3C8C93">
    <meta name="msapplication-TileImage" content="/img/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#3C8C93">

    <meta property="og:url" content="{{ env('APP_URL') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ __('desc') }}"/>
    <meta property="og:image" content="{{ env('APP_URL') }}/img/logo.png"/>
    <meta property="fb:app_id" content="1863540353738559"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @hasSection('ld+json')
        @yield('ld+json')
    @else
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "legalName": "tik.pt",
            "url": "{{ env('APP_URL') }}",
            "logo": "{{ env('APP_URL') }}/img/logo.png",
            "description": "{{ __('desc') }}",
            "foundingDate": "2018-01-01",
            "contactPoint": [
                {
                    "@type": "ContactPoint",
                    "telephone": "+351 968422730",
                    "contactType": "customer service"
                }
            ]
        }
    </script>
    @endif

    @yield('style')
</head>
<body class="@yield('bodyClass')">
    <p class="hide">{{ __('desc') }}</p>
    <div id="app">
        @include('layouts.navbar')
        
        @yield('content')

        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script>
        window.Laravel = {token: '{{ csrf_token() }}'};
        window.components = { };
        window.utils = { };
        window.translations = {month: [
            '{{ __('Jan') }}', '{{ __('Feb') }}', '{{ __('Mar') }}', '{{ __('Apr') }}',
            '{{ __('May') }}', '{{ __('Jun') }}', '{{ __('Jul') }}', '{{ __('Aug') }}',
            '{{ __('Sep') }}', '{{ __('Oct') }}', '{{ __('Nov') }}', '{{ __('Dec') }}']};
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        if(typeof onDocumentReady === 'function')
            document.addEventListener("DOMContentLoaded", onDocumentReady);
    </script>
    @yield('script')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-26516943-10"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-26516943-10');
    </script>
</body>
</html>
