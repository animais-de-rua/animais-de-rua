<span>
    @if ($entry->{$column['name']} && count($entry->{$column['name']}))
        @foreach ($entry->{$column['name']} as $file_path)
        	@php
        		$image = isset($column['disk']) ? asset(\Storage::disk($column['disk'])->url($file_path)) : asset($file_path);
        	@endphp
        	<a target="_blank" href="{{ $image }}">
        		<img width="140" src="{{ $image }}" />
        	</a>
        @endforeach
    @else
        -
    @endif
</span>
