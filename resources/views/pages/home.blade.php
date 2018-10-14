@extends('layouts.app')

@section('content')
<div id="home">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['association_title'] }}</h1>
                        <p>{!! $page['association_text'] !!}</p>
                        <a href="/association" class="link lined">{{ $page['association_link'] }}</a>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                <picture>
                    <source srcset="img/home.webp" type="image/webp"/>
                    <source srcset="img/home.jpg" type="image/jpeg"/>
                    <img src="img/home.jpg" alt="Home"/>
                </picture>
            </div>
        </div>
    </div>

    <div class="container processes">
        <h1>{{ $page['processes_title'] }}</h1>
        <p>{{ $page['processes_subtitle'] }}</p>
        <div class="flex-slider">
            @php
            $pages = ceil(count($processes) / 3);
            @endphp
            <ul>
                @for($i=0; $i<$pages; $i++)
                <li>
                    <div class="slide">
                        @for($j=0; $j<3 && isset($processes[$j + $i * 3]); $j++)
                        @php
                            $process = $processes[$j + $i * 3]
                        @endphp
                        <div class="card">
                            <div class="image">
                                @if(count($process->images))
                                <div style="background-image:url('{{ str_replace('process', 'process/thumb', $process->images[0]) }}')"></div>
                                @endif
                            </div>

                            <div>
                                <h1>{{ $process->name }}</h1>

                                <div class="necessity">
                                    <p>{{ __("Need") }}</p>
                                    <div class="line"></div>
                                    @switch($process->status)
                                        @case('waiting_godfather')
                                        <p>{{ __('godfathers') }}</p>
                                        @break

                                        @case('waiting_capture')
                                        <p>{{ __('capture') }}</p>
                                        @break
                                    @endswitch
                                </div>
                            </div>
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

    <div class="container campaigns">
        <div class="flex-slider" auto-scroll="5000">
            <ul>
                @foreach($campaigns as $campaign)
                <li>
                    <div class="slide">
                        <div>
                            <img src="uploads/{{ $campaign->image }}" alt="{{ $campaign->name }}"/>
                        </div>
                        <div>
                            <blockquote>{{ __("campaigns") }}</blockquote>
                            <h1>{{ $campaign->name }}</h1>
                            <p>{{ $campaign->introduction }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <ul class="dots">
                @foreach($campaigns as $i => $campaign)
                <li class="{{ $i == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ul>
        </div>
    </div>

    @component('components.banner.risk')
    @endcomponent

    <div class="container how-to-help">
        <h2>{{ $page["help_title"] }}</h2>
        <div class="row row-wrap header">
            @foreach(['volunteer', 'friend', 'godfather', 'donate'] as $link)
            <a href="/help#{{ $link }}" class="column box link">
                <h3>{{ $page["help_{$link}_title"] }}</h3>
                <p>{{ $page["help_{$link}_text"] }}</p>
                <div class="icon icon-arrow"></div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
