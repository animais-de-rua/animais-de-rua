<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
                <!-- img alt="{{ config('app.name', 'Laravel') }}" src="/img/icons/android-icon-48x48.png" /-->
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <!--ul class="nav navbar-nav">
            </ul-->

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right links">
                <!-- Authentication Links -->
                @guest
                    <li><a href="{{ route('login') }}">{{ __("Login") }}</a></li>
                    <li><a href="{{ route('register') }}">{{ __("Register") }}</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle dropdown-toggle-avatar" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                            <div class="avatar" style="background-image: url('{{ Auth::user()->avatar }}');"></div>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ '/admin' }}">{{ __("Dashboard") }}<i class="ico-stack" style="float:right;margin-top:1px;"></i></a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    {{ __("Logout") }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>