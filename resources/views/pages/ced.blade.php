@extends('layouts.app')

@section('content')
<div id="ced">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['ced_title'] }}</h1>
                        <p>{!! $page['ced_text'] !!}</p>
                        <a href="/ced" class="link lined">{{ $page['ced_link'] }}</a>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                <picture>
                    <source srcset="img/ced.webp" type="image/webp"/>
                    <source srcset="img/ced.jpg" type="image/jpeg"/>
                    <img src="img/ced.jpg" alt="CED"/>
                </picture>
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
                @foreach(json_decode($page['info_links']) as $link)
                    <li><a href="{{ $link->url }}">{{ $link->name }} <span class="icon icon-arrow"></span></a></li>
                @endforeach
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
                <picture>
                    <source srcset="img/ced02.webp" type="image/webp"/>
                    <source srcset="img/ced02.jpg" type="image/jpeg"/>
                    <img src="img/ced02.jpg" alt="CED"/>
                </picture>
            </div>
            <div class="column column-50">
                <picture>
                    <source srcset="img/ced01.webp" type="image/webp"/>
                    <source srcset="img/ced01.jpg" type="image/jpeg"/>
                    <img src="img/ced01.jpg" alt="CED"/>
                </picture>
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
