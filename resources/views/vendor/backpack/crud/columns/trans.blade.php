{{-- regular object attribute --}}
@php
	$value = ucfirst(__($entry->{$column['name']}));
@endphp

<span>
	{{ (array_key_exists('prefix', $column) ? $column['prefix'] : '').str_limit(strip_tags($value), array_key_exists('limit', $column) ? $column['limit'] : 50, "[...]").(array_key_exists('suffix', $column) ? $column['suffix'] : '') }}
</span>
