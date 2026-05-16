@extends(backpack_view('layouts.plain'))

@section('before_styles')
<style>
.social-logins {
    display: flex;
    justify-content: center;
    margin-bottom: 16px;
}
.btn.btn-social {
    display: flex;
    padding: 10px 0 10px 56px!important;
    border: 0;
    border-radius: 3px!important;
    width: 50%;
    max-width: 240px;
}
.btn-facebook {
    color: #fff;
    background-color: #3b5998;
    border-color: rgba(0,0,0,0.2);
}
.btn-social>:first-child {
    border: 0;
    padding: 4px;
    width: 42px!important;
    border-right: 0!important;
}
</style>
@endsection
@section('content')
    <div class="row mt-5">
        <div class="col-md-4 offset-md-4">
            <h3 class="text-center mb-4">{{ trans('backpack::base.login') }}</h3>
            <div class="card">
                <div class="card-body">
                    <form class="col-md-12 pt-3" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label>{{ config('backpack.base.authentication_column_name') }}</label>
                            <input type="text" class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}" name="{{ $username }}" value="{{ old($username) }}">
                            @if ($errors->has($username))
                                <div class="invalid-feedback">{{ $errors->first($username) }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>{{ trans('backpack::base.password') }}</label>
                            <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">{{ trans('backpack::base.remember_me') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">
                                {{ trans('backpack::base.login') }}
                            </button>
                        </div>

	                    <hr />

	                    <div>
	                        @include('layouts.social_login')
	                    </div>
                    </form>
                </div>
            </div>
            @if (backpack_users_have_email())
                <div class="text-center mt-3"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
            @endif
            @if (config('backpack.base.registration_open'))
                <div class="text-center mt-3"><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></div>
            @endif
        </div>
    </div>
@endsection
