@extends('layouts.app')

@section('title', $title)

@section('content')
<div page-title="{{ $title }}"></div>

<div id="donation">
    <div class="list">
        <h1>{{ __("Donate") }}</h1>
        <p>{{ __("web.donate.title") }}</p>

        <ul>
            <li class="selectable">
                <img src="/img/svg/mbway.svg">
                <p>{{ Config::get('app.donate.mbway') }}</p>
            </li>
            <a href="https://{{ Config::get('app.donate.paypal') }}" target="_blank">
                <li class="paypal">
                    <img src="/img/svg/paypal.svg">
                    <p>{{ Config::get('app.donate.paypal') }}</p>
                </li>
            </a>
            <li class="selectable">
                <img src="/img/svg/bank.svg">
                <p>{{ Config::get('app.donate.iban') }}</p>
            </li>
        </ul>
    </div>

    @php
    $files = glob("img/error/*.jpg");
    @endphp
    <div class="image" style="background-image: url('/{{ $files[array_rand($files)] }}');"></div>
</div>

@endsection
