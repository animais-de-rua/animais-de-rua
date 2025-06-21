<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ Config::get('app.name') }}</title>

    {{-- Fonts --}}
    <script src="{{ mix('js/app.js') }}"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">

    {{-- Styles --}}
    <style>
        html,
        body {
            color: #a5aaac;
            font-family: 'Lato', sans-serif;
            font-size: 80%;
            height: 100vh;
        }

        .flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .top-right {
            position: absolute;
            right: 1rem;
            top: 2rem;
        }

        .links>a {
            color: #a5aaac;
            padding: 0.5rem 2rem;
            font-size: 13px;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 0.2rem;
        }

        .cookie-consent {
            position: absolute;
            padding: 1rem 4rem;
            box-shadow: 0 0 0.5rem;
            border-radius: 0.3rem;
            bottom: 2vh;
            background-color: #FFF;
        }
    </style>
</head>

<body class="flex-center full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth('backpack')
                @if (is('admin'))
                    <a href="{{ url('/admin') }}">{{ __('Admin') }}</a>
                @endif

                <a href="{{ url('/admin/logout') }}">{{ __('Logout') }}</a>
            @else
                <a href="{{ url('/admin/login') }}">{{ __('Login') }}</a>

                @if (Route::has('register'))
                    <a href="{{ url('/admin/register') }}">{{ __('Register') }}</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <img width="280" src="{{ url('/img/logo/logo-text.png') }}">
    </div>
</body>

</html>
