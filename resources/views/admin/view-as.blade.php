<style>
.roles-list li a {
	width: 100%;
}
.roles-list li p {
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
.roles-list li a {
	display: flex;
	align-items: center;
}
.dropdown-menu > li > a {
	padding: 3px 25px;
}
.dropdown-menu > .active > a {
	pointer-events: none;
}
.dropdown-menu > .toggle.active > a {
	pointer-events: initial;
}
.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
	background-color: #d2d6de;
	color: #000;
}
.dropdown-menu > .title {
	padding: 0px 25px 4px;
    font-size: 12px;
}
li.toggle.active p:before {
    content: '✓ ';
    margin-left: -15px;
}
hr {
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
<li class="dropdown roles-list">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		@if($current_role != 'admin')
		<i class="fa fa-btn fa-lock"></i>&nbsp;&nbsp;{{ ucfirst(__($current_role)) }}
		@else
		<i class="fa fa-btn fa-unlock"></i>
		@endif
		<span class="caret"></span>
	</a>
	<ul class="dropdown-menu" role="menu">
		<li class="title">{{ __("View as") }}:</li>
		@foreach($roles as $role)
		<li class="{{ $role == $current_role ? "active" : "" }}">
			<a href="{{ route('view-as-role', ['role' => $role]) }}">
				<p>{{ ucfirst(__($role)) }}</p>
			</a>
		</li>
		@endforeach
		<hr />
		@foreach($permissions as $permission)
		@php
			$state = in_array($permission, $current_permissions);
		@endphp
		<li class="toggle {{ $state ? "active" : "" }}">
			<a href="{{ route('view-as-permission', ['permission' => $permission, 'state' => $state ? 0 : 1]) }}">
				<p>{{ ucfirst(__($permission)) }}</p>
			</a>
		</li>
		@endforeach
		<hr />
		@foreach($headquarters as $headquarter)
		@php
			$state = in_array($headquarter->id, $current_headquarters);
		@endphp
		<li class="toggle {{ $state ? "active" : "" }}">
			<a href="{{ route('view-as-headquarter', ['headquarter' => $headquarter->id, 'state' => $state ? 0 : 1]) }}">
				<p>{{ $headquarter->name }}</p>
			</a>
		</li>
		@endforeach
		<hr />
		<li>
			<a href="{{ route('view-as-permission', ['permission' => 'all', 'state' => 0]) }}">
				<p>{{ __("Clear all") }}</p>
			</a>
		</li>
	</ul>
</li>
