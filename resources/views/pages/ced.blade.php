@extends('layouts.app')

@section('title', $title)

@section('content')
<div page-title="{{ $title }}"></div>

<div id="ced">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['ced_title'] }}</h1>
                        <p>{!! $page['ced_text'] !!}</p>
                        @if($page['ced_link'])
                        <a href="/ced" class="link lined">{{ $page['ced_link'] }}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'ced'])
                @endcomponent
            </div>
        </div>
    </div>

    <div class="container whatisit">
        <div class="row row-wrap">
            <div class="column column-50 column-offset-25">
                <h1 class="label-title">{{ $page['what_title'] }}</h1>
                <p>{!! $page['what_text'] !!}</p>
                <hr />
                <h3>{!! $page['info_title'] !!}</h3>
                <ul class="arrow-links">
                @if($page['info_links'])
                    @foreach(json_decode($page['info_links']) as $link)
                        <li><a href="{{ $link->url }}" target="_blank" onclick="app.track('ViewContent', {'path': {{ $link->url }}, 'name': {{ $link->name }} })" >{{ $link->name }} <span class="icon icon-arrow"></span></a></li>
                    @endforeach
                @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="container advantage">
        <div class="row row-wrap">
            <div class="column">
                <h1 class="label-title">{{ $page['advantage_title'] }}</h1>
            </div>

            <div class="column column-50">
                <div class="white-box">
                    <h2>{{ $page['advantage_community_title'] }}</h1>
                    <div class="dash-list">{!! $page['advantage_community_text'] !!}</div>
                </div>
            </div>

            <div class="column column-50">
                <div class="white-box">
                    <h2>{{ $page['advantage_colony_title'] }}</h1>
                    <div class="dash-list">{!! $page['advantage_colony_text'] !!}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row row-wrap">
            <div class="column column-50">
                @component('components.picture', ['image' => 'ced02', 'lazy' => true])
                @endcomponent
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'ced01', 'lazy' => true])
                @endcomponent
            </div>
        </div>
    </div>

    <div class="container failed">
        <div class="row row-wrap">
            <div class="column column-50 column-offset-25">
                <h1 class="label-title">{{ $page['alternatives_title'] }}</h1>
                <p>{!! $page['alternatives_text'] !!}</p>
                <hr />

                @foreach (["capture", "feed", "greeting", "nothing"] as $accordion)
                <div class="accordion {{ $loop->first ? "open" : "" }}">
                    <h1>{{ $page["alternatives_{$accordion}_title"] }}</h1>
                    <div class="slider">
                        <div>{!! $page["alternatives_{$accordion}_text"] !!}</div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    @component('components.banner.risk')
    @endcomponent

</div>
@endsection
