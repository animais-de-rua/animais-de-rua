<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
        {{ isset($title) ? $title.' - '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
    </title>

    @yield('before_styles')
    @stack('before_styles')

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/Ionicons/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link rel="stylesheet" href="{{ asset('vendor/backpack/backpack.base.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/overlays/backpack.bold.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/icomoon/style.css') }}">

    <style type="text/css">
        .skin-red .main-header .navbar,
        .skin-red .main-header .navbar .sidebar-toggle:hover,
        .skin-red .main-header .logo,
        .skin-red .main-header li.user-header {
            background-color: #E53625;
        }
        .skin-red .sidebar-menu > li.active > a {
            border-color: #E53625;
        }
    </style>

    @yield('after_styles')
    @stack('after_styles')

    {{-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --}}
    {{-- WARNING: Respond.js doesn't work if you view the page via file:// --}}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition {{ config('backpack.base.skin') }} sidebar-mini">
	<script type="text/javascript">
		(function () {
			if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
				var body = document.getElementsByTagName('body')[0];
				body.className = body.className + ' sidebar-collapse';
			}
		})();
	</script>
    <div class="wrapper">
        <header class="main-header">
            <a href="{{ url('') }}" class="logo">
              <span class="logo-mini"><img style="width: 38px; filter: brightness(0) invert(1) opacity(0.9);" src="/img/logo/logo.svg" /></span>
              <span class="logo-lg"><img style="width: 80%; max-width: 300px; filter: brightness(0) invert(1) opacity(0.9);" src="/img/logo/logo-text.svg" /></span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('backpack::base.toggle_navigation') }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                @include('backpack::inc.menu')
            </nav>
        </header>

        @include('backpack::inc.sidebar')

        <div class="content-wrapper">
            @yield('header')

            <section class="content">
                @yield('content')
            </section>
        </div>

        <footer class="main-footer">
            @if (config('backpack.base.show_powered_by'))
            <div class="pull-right hidden-xs">
                {{ trans('backpack::base.powered_by') }} <a target="_blank" href="http://backpackforlaravel.com?ref=panel_footer_link">Backpack for Laravel</a>
            </div>
            @endif
            {{ trans('backpack::base.handcrafted_by') }} <a target="_blank" href="{{ config('backpack.base.developer_link') }}">{{ config('backpack.base.developer_name') }}</a>.
        </footer>
    </div>

    @yield('before_scripts')
    @stack('before_scripts')

    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    {{-- <script src="{{ asset('vendor/adminlte') }}/bower_components/fastclick/lib/fastclick.js"></script> --}}
    <script src="{{ asset('vendor/adminlte') }}/dist/js/adminlte.min.js"></script>

    <script type="text/javascript">
        {{-- Store sidebar state --}}
        $('.sidebar-toggle').click(function(event) {
            event.preventDefault();
            if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                sessionStorage.setItem('sidebar-toggle-collapsed', '');
            } else {
                sessionStorage.setItem('sidebar-toggle-collapsed', '1');
            }
        });

        {{-- To make Pace works on Ajax calls --}}
        $(document).ajaxStart(function() { Pace.restart(); });

        {{-- Ajax calls should always have the CSRF token attached to them, otherwise they won't work --}}
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        {{-- Set active state on menu element --}}
        var current_url = "{{ Request::fullUrl() }}";
        var full_url = current_url+location.search;
        var $navLinks = $("ul.sidebar-menu li a");
        {{-- First look for an exact match including the search string --}}
        var $curentPageLink = $navLinks.filter(
            function() { return $(this).attr('href') === full_url; }
        );
        {{-- If not found, look for the link that starts with the url --}}
        if(!$curentPageLink.length > 0){
            $curentPageLink = $navLinks.filter(
                function() { return $(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href')); }
            );
        }

        $curentPageLink.parents('li').addClass('active');
        {{-- Enable deep link to tab --}}
        var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
        location.hash && activeTab && activeTab.tab('show');
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            location.hash = e.target.hash.replace("#tab_", "#");
        });
    </script>

    @include('backpack::inc.alerts')

    @yield('after_scripts')
    @stack('after_scripts')

    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
</body>
</html>
