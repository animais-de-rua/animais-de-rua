<style>
.flags-list .dropdown-item {
	width: 100%;
	display: flex;
	align-items: center;
}
.flags-list .dropdown-item p {
	display: inline-block;
	margin: 0;
}
.flags-list .flag {
	width: 40px;
	height: 28px;
	display: inline-block;
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
	vertical-align: middle;
}
.flags-list .dropdown-item.active {
	pointer-events: none;
}
.flags-list .dropdown-item.active, .flags-list .dropdown-item.active:focus, .flags-list .dropdown-item.active:hover {
	background-color: #d2d6de;
	color: #000;
}
</style>

@php
$lang = Session::get('locale', 'en');
$locales = config('backpack.crud.locales');
@endphp
<li class="nav-item dropdown flags-list">
	<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		<div class="flag" style="background-image: url({{ asset("img/flags/$lang.png") }}); height: 20px;"></div>
		{{-- {{ $locales[$lang] }} --}}
	</a>
	<div class="dropdown-menu">
		@foreach($locales as $local => $label)
		<a class="dropdown-item {{ $local == $lang ? 'active' : '' }}" href="{{ route('lang', ['locale' => $local]) }}">
			<div class="flag" style="background-image: url({{ asset("img/flags/$local.png") }})"></div>
			<p>{{ $label }}</p>
		</a>
		@endforeach
	</div>
</li>
