@extends('layouts.app')

@section('content')
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
                <picture>
                    <source srcset="img/partners.webp" type="image/webp"/>
                    <source srcset="img/partners.jpg" type="image/jpeg"/>
                    <img src="img/partners.jpg" alt="partners"/>
                </picture>
            </div>
        </div>
    </div>

    <div class="sponsors">
        <div class="container">
            <h1 class="title">{{ $page['partners_title'] }}</h1>
            <div class="grid">
                @foreach($sponsors as $sponsor)
                <a href="{{ $sponsor->url }}" target="_blank">
                    <img src="{{ url('uploads/' . $sponsor->image) }}" alt="{{ $sponsor->name }}" />
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
