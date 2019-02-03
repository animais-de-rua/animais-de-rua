@extends('layouts.app')

@section('title', $title)

@section('content')
<div page-title="{{ $title }}"></div>

<div id="privacy-policy">
    <div class="container privacy">
        <h1>{{ $page['privacypolicy_title'] }}</h1>
        <h3>{{ $page['privacypolicy_subtitle'] }}</h3>
        <div class="doc">{!! $page['privacypolicy_text'] !!}</div>
    </div>
</div>
@endsection
