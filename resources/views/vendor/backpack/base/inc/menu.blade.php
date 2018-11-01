<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        {{-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>{{ __("Home") }}</span></a></li> --}}
        {{-- <li><a href="{{ url('/store') }}"><i class="fa fa-shopping-cart"></i> <span>{{ __("Store") }}</span></a></li> --}}
    </ul>
</div>


<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      {{-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>Home</span></a></li> --}}

      @component('admin.flagpicker')
      @endcomponent

      @if(admin())
        @component('admin.view-as')
        @endcomponent
      @endif

      @if (config('backpack.base.setup_auth_routes'))
        @if (backpack_auth()->guest())
            <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/login') }}">{{ trans('backpack::base.login') }}</a></li>
            @if (config('backpack.base.registration_open'))
            <li><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
            @endif
        @else
            <li><a href="{{ route('backpack.auth.logout') }}"><i class="fa fa-btn fa-sign-out"></i> {{ trans('backpack::base.logout') }}</a></li>
        @endif
       @endif

    </ul>
</div>
