@extends('layouts.app')

@php
$locale = Session::get('locale', 'pt');
@endphp

@section('facebook')
    <meta property="og:title" content="{{ env('APP_NAME') }}: {{ $animal['name'] }}"/>
    <meta property="og:description" content="{{ strip_tags($animal['history'][$locale] ?? '') }}"/>
    <meta property="og:image" content="{{ env('APP_URL') . $animal['images'][0] }}"/>
@endsection

@section('content')
<div id="animals-view">
    <div class="container detail">
        <div class="row row-wrap">
            <div class="column column-40 column-offset-10">
                <h1>{{ $animal['name'] }}</h1>
                <h2>{{ $animal['county'] }}, {{ $animal['district'] }}</h2>
                @php
                    $date = strtotime($animal['created_at']);
                @endphp
                <p class="date"><span>{{ __("Beginning of the process") }}</span><br/>{{ __(date("F", $date)) }}, {{ date("Y", $date) }}</p>
                <div>
                    @if($animal['history'] && gettype($animal['history']) == 'array')
                    {!! $animal['history'][$locale] ?? '' !!}
                    @else
                    {!! $animal['history'] ?? '' !!}
                    @endif
                </div>
                @if($option == 'godfather')
                <button class="btn dark" onclick="return modal.openGodfather()">{{ __("Become a Godfather") }}</button>
                @endif
            </div>
            <div class="column column-40">
                @if($animal['images'])
                <div class="flex-slider swipeable" auto-scroll="3500">
                    <ul class="touchable">
                        @foreach($animal['images'] as $image)
                        <li>
                            <img src="/{{ $image }}" alt="{{ $animal['name'] }}" loading="lazy"/>
                        </li>
                        @endforeach
                    </ul>
                    <ul class="dots">
                        @foreach($animal['images'] as $i => $image)
                        <li class="{{ $i == 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        <div class="row row-wrap">
            <div class="column column-40 column-offset-50">
                <div class="share">
                    <p>{{ __("Share") }}</p>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->full() }}" onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false" target="_blank" title="{{ __("Share on Facebook") }}">
                        <i class="icon icon-facebook"></i>
                    </a>
                    <a href="https://twitter.com/share?url={{ url()->full() }}&via=Animais_de_Rua&text={{ $animal['name'] }}" onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false" target="_blank" title="{{ __("Share on Twitter") }}">
                        <i class="icon icon-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if(isset($other))
    <div class="container region three-slider">
        <div class="row row-wrap">
            <h1>{{ __("web.animals.territory") }}</h1>
        </div>
        <div class="row row-wrap">
            <div class="column column-80 column-offset-10 slide">
                @foreach($other as $animal)
                <div class="card">
                    <a class="link" href="/animals/adoption/{{ $animal['id'] }}">
                        <div class="image">
                            <div style="background-image:url('/{{ $animal['images'][0] }}')"></div>
                        </div>
                        <h1>{{ $animal['name'] }}</h1>
                        @php
                        $limit = 100;
                        $history = preg_replace("/<p[^>]*?>/", "", $animal['history']);
                        if (strlen($history) > $limit)
                            $history = substr($history, 0, $limit) . '...';
                        @endphp
                        <p>{!! $history !!}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

@endsection
