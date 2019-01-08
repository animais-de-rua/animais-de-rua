<div class="box">
    <div class="box-body box-profile">
	    <img class="profile-user-img img-responsive img-circle" src="{{ backpack_avatar_url(backpack_auth()->user()) }}">
	    <h3 class="profile-username text-center">{{ backpack_auth()->user()->name }}</h3>
	</div>

	<ul class="nav nav-pills nav-stacked">

	  <li role="presentation"
		@if (Request::route()->getName() == 'backpack.account.info')
	  	class="active"
	  	@endif
	  	><a href="{{ route('backpack.account.info') }}">{{ trans('backpack::base.update_account_info') }}</a></li>

	  <li role="presentation"
		@if (Request::route()->getName() == 'backpack.account.password')
	  	class="active"
	  	@endif
	  	><a href="{{ route('backpack.account.password') }}">{{ trans('backpack::base.change_password') }}</a></li>

	  @if (admin())
	  <li role="presentation"
		@if (Request::route()->getName() == 'terminal')
	  	class="active"
	  	@endif
	  	><a href="{{ route('terminal') }}">{{ trans('Artisan Terminal') }}</a></li>

	  <li role="presentation"
		@if (Request::route()->getName() == 'symlink')
	  	class="active"
	  	@endif
	  	><a href="{{ route('symlink') }}">{{ trans('Symlink') }}</a></li>
	  @endif
	</ul>
</div>
