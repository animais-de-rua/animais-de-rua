<style>
.flags-list li a {
	width: 100%;
}
.flags-list li p {
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
.flags-list li a {
	display: flex;
	align-items: center;
}
.dropdown-menu > .active > a {
	pointer-events: none;
}
.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
	background-color: #d2d6de;
	color: #000;
}
</style>

@php
$lang = Session::get('locale', 'en');
$locales = config('backpack.crud.locales');
@endphp
<li class="dropdown flags-list">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		<div class="flag" style="background-image: url({{ asset("img/flags/$lang.png") }}); height: 20px;"></div>
		{{-- {{ $locales[$lang] }} --}}
		<span class="caret"></span>
	</a>
	<ul class="dropdown-menu" role="menu">
		@foreach($locales as $local => $label)
		<li class="{{ $local == $lang ? "active" : "" }}">
			<a href="{{ route('lang', ['locale' => $local]) }}">
				<div class="flag" style="background-image: url({{ asset("img/flags/$local.png") }})"></div>
				<p>{{ $label }}</p>
			</a>
		</li>
		@endforeach
	</ul>
</li>