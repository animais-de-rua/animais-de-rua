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
                    <source srcset="/img/home.webp" type="image/webp"/>
                    <source srcset="/img/home.jpg" type="image/jpeg"/>
                    <img src="/img/home.jpg" alt="Home"/>
                </picture>
            </div>
        </div>
    </div>

    @component('components.urgent-help', ['page' => $page, 'processes' => $processes])
    @endcomponent

    <div class="container campaigns">
        <div class="flex-slider swipeable" auto-scroll="5000">
            <ul class="touchable">
                @foreach($campaigns as $campaign)
                <li>
                    <div class="slide">
                        <div>
                            <img src="/uploads/{{ $campaign->image }}" alt="{{ $campaign->name }}"/>
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
