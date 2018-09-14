@php
$lang = Session::get('locale', 'en');
$locales = config('backpack.crud.locales');
@endphp

@foreach($locales as $local => $label)
<a href="{{ route('lang', ['locale' => $local]) }}">
	<div class="lang{{ $local == $lang ? " active" : "" }}">{{ strtoupper(__("$local")) }}</div>
</a>
@endforeach
