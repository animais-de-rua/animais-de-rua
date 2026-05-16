@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row mt-5">
        <div class="col-md-4 offset-md-4">
            <h3 class="text-center mb-4">{{ trans('backpack::base.reset_password') }}</h3>
            <ul class="nav nav-tabs mb-0">
                  <li class="nav-item"><a class="nav-link disabled text-muted"><strong>{{ trans('backpack::base.step') }} 1.</strong> {{ trans('backpack::base.confirm_email') }}</a></li>
                  <li class="nav-item"><a class="nav-link active"><strong>{{ trans('backpack::base.step') }} 2.</strong> {{ trans('backpack::base.choose_new_password') }}</a></li>
            </ul>
            <div class="tab-content border border-top-0 p-3">
              <div class="tab-pane active" id="tab_1">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="col-md-12 pt-3" role="form" method="POST" action="{{ route('backpack.auth.password.reset') }}">
                    {!! csrf_field() !!}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label>{{ trans('backpack::base.email_address') }}</label>
                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ trans('backpack::base.new_password') }}</label>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ trans('backpack::base.confirm_new_password') }}</label>
                        <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">
                        @if ($errors->has('password_confirmation'))
                            <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">
                            {{ trans('backpack::base.change_password') }}
                        </button>
                    </div>
                </form>
                <div class="clearfix"></div>
              </div>
            </div>
        </div>
    </div>
@endsection
