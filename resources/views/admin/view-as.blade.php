<style>
.roles-list .dropdown-item {
	width: 100%;
	display: flex;
	align-items: center;
	padding: 3px 25px;
}
.roles-list .dropdown-item p {
	display: inline-block;
	margin: 0;
}
.roles-list .flag {
	width: 40px;
	height: 28px;
	display: inline-block;
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
	vertical-align: middle;
}
.roles-list .dropdown-item.active {
	pointer-events: none;
}
.roles-list .dropdown-item.toggle.active {
	pointer-events: initial;
}
.roles-list .dropdown-item.active, .roles-list .dropdown-item.active:focus, .roles-list .dropdown-item.active:hover {
	background-color: #d2d6de;
	color: #000;
}
.roles-list .dropdown-header {
	padding: 0px 25px 4px;
	font-size: 12px;
}
.roles-list .dropdown-item.toggle.active p:before {
	content: '✓ ';
	margin-left: -15px;
}
.roles-list .dropdown-divider {
	margin: 8px 0;
}
</style>

@php
$current_role = Session::get('role', 'admin');
$current_permissions = Session::get('permissions', []);
$current_headquarters = Session::get('headquarters', []);
$roles = Config::get("enums.user.roles");
$permissions = Config::get("enums.user.permissions");
$headquarters = \App\Models\Headquarter::select(['id', 'name'])->get();
@endphp
<li class="nav-item dropdown roles-list">
	<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		@if($current_role != 'admin')
		<i class="fa fa-btn fa-lock"></i>&nbsp;&nbsp;{{ ucfirst(__($current_role)) }}
		@else
		<i class="fa fa-btn fa-unlock"></i>
		@endif
	</a>
	<div class="dropdown-menu">
		<h6 class="dropdown-header">{{ __("View as") }}:</h6>
		@foreach($roles as $role)
		<a class="dropdown-item {{ $role == $current_role ? 'active' : '' }}" href="{{ route('view-as-role', ['role' => $role]) }}">
			<p>{{ ucfirst(__($role)) }}</p>
		</a>
		@endforeach
		<div class="dropdown-divider"></div>
		@foreach($permissions as $permission)
		@php
			$state = in_array($permission, $current_permissions);
		@endphp
		<a class="dropdown-item toggle {{ $state ? 'active' : '' }}" href="{{ route('view-as-permission', ['permission' => $permission, 'state' => $state ? 0 : 1]) }}">
			<p>{{ ucfirst(__($permission)) }}</p>
		</a>
		@endforeach
		<div class="dropdown-divider"></div>
		@foreach($headquarters as $headquarter)
		@php
			$state = in_array($headquarter->id, $current_headquarters);
		@endphp
		<a class="dropdown-item toggle {{ $state ? 'active' : '' }}" href="{{ route('view-as-headquarter', ['headquarter' => $headquarter->id, 'state' => $state ? 0 : 1]) }}">
			<p>{{ $headquarter->name }}</p>
		</a>
		@endforeach
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="{{ route('view-as-permission', ['permission' => 'all', 'state' => 0]) }}">
			<p>{{ __("Clear all") }}</p>
		</a>
	</div>
</li>
