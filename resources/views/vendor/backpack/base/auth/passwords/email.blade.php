@extends(backpack_view('layouts.plain'))

<!-- Main Content -->
@section('content')
    <div class="row mt-5">
        <div class="col-md-4 offset-md-4">
            <h3 class="text-center mb-4">{{ trans('backpack::base.reset_password') }}</h3>
            <ul class="nav nav-tabs mb-0">
              <li class="nav-item"><a href="#tab_1" class="nav-link active" data-toggle="tab"><strong>{{ trans('backpack::base.step') }} 1.</strong> {{ trans('backpack::base.confirm_email') }}</a></li>
              <li class="nav-item"><a class="nav-link disabled text-muted"><strong>{{ trans('backpack::base.step') }} 2.</strong> {{ trans('backpack::base.choose_new_password') }}</a></li>
            </ul>
            <div class="tab-content border border-top-0 p-3">
              <div class="tab-pane active" id="tab_1">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @else
                <form class="col-md-12 pt-3" role="form" method="POST" action="{{ route('backpack.auth.password.email') }}">
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label>{{ trans('backpack::base.email_address') }}</label>
                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">
                            {{ trans('backpack::base.send_reset_link') }}
                        </button>
                    </div>
                </form>
                @endif
                <div class="clearfix"></div>
              </div>
            </div>

            <div class="text-center mt-3">
              <a href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>

              @if (config('backpack.base.registration_open'))
              / <a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a>
              @endif
            </div>
        </div>
    </div>
@endsection
