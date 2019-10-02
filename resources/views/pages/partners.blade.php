@extends('layouts.app')

@section('title', $title)

@section('content')
<div page-title="{{ $title }}"></div>

<div id="partners">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['partners_title'] }}</h1>
                        <p>{!! $page['partners_text'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'partners'])
                @endcomponent
            </div>
        </div>
    </div>

    <div class="sponsors">
        <div class="container">
            <h1 class="title">{{ $page['partners_title'] }}</h1>
            <div class="grid">
                @foreach($sponsors as $sponsor)
                <a href="{{ $sponsor->url }}" target="_blank">
                    <img src="{{ url('uploads/' . $sponsor->image) }}" alt="{{ $sponsor->name }}" loading="lazy"/>
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
