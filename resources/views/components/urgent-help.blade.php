<div class="container processes">
    <h1>{{ $page['processes_title'] }}</h1>
    <p>{{ $page['processes_subtitle'] }}</p>
    <div class="flex-slider swipeable">
        @php
        $pages = ceil(count($processes) / 3);
        @endphp
        <ul class="touchable">
            @for($i=0; $i<$pages; $i++)
            <li>
                <div class="slide">
                    @for($j=0; $j<3 && isset($processes[$j + $i * 3]); $j++)
                    @php
                        $process = $processes[$j + $i * 3];
                    @endphp
                    <div class="card">
                        <a class="link" href="/animals/godfather/{{ $process->id }}">
                            <div class="image">
                                @if($process->images && count($process->images))
                                <div style="background-image:url('{{ str_replace('process', 'process/thumb', $process->images[0]) }}')"></div>
                                @endif
                            </div>
                            <div>
                                <h1>{{ $process->name }}</h1>
                                <div class="necessity">
                                    <p>{{ __("Need") }}</p>
                                    <div class="line"></div>
                                    <p>{{ __('godfathers') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endfor
                </div>
            </li>
            @endfor
        </ul>
        <ul class="dots">
            @for($i=0; $i<$pages; $i++)
            <li class="{{ $i == 0 ? 'active' : '' }}"></li>
            @endfor
        </ul>
    </div>
</div>
