@extends(backpack_view('blank'))

@section('content')
<div class="jumbotron mt-4 p-5 bg-body-secondary rounded">
    <h1 class="display-3">{{ __("backpack::base.welcome") }}</h1>
    <p>{{ __("backpack::base.use_sidebar") }}</p>

    @if(admin())
    <hr />
    <div class="box">
        <div class="box-header with-border">
            <a data-bs-toggle="collapse" href="#collapseOne">
                <div class="btn btn-primary">{{ __("gemadigital::messages.admin_actions") }} <span class="la la-key" aria-hidden="true" style="margin-left: 10px;"></span></div>
            </a>
        </div>
        <div id="collapseOne" class="collapse out mt-4">
            <div class="panel-body">
                @include('gemadigital::admin.actions_buttons')
            </div>
            <hr />
        </div>
    </div>
    @endif

    @if(config('gemadigital.build.enabled'))
    <div class="box mt-3">
        @include('gemadigital::admin.build_button')
    </div>
    @endif
</div>
@endsection
