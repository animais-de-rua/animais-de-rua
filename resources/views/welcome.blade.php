<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Animais de Rua</title>

        <style>
            html, body {
                background-color: #222d32;
                color: #636b6f;
                font-family: sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .logo {
                width: 58px;
                margin-bottom: 12px;
                animation: logo 1s 0.4s 1 forwards;
                overflow: hidden;
            }
            .logo img {
                width: 380px;
                filter: brightness(0) invert(1) opacity(0.9);
            }
            @keyframes logo {
                from { width: 58px; }
                to { width: 380px; }
            }

            .links > a {
                color: #FFF;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <!--div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div-->
            @endif

            <div class="content">
                <div class="logo">
                    <img src="img/logo/logo-text.svg" />
                </div>

                <div class="links">
                    <a href="/admin">Admin</a>
                </div>
            </div>
        </div>
    </body>
</html>
