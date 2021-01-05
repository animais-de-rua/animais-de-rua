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
                <div>
                    <img src="/img/svg/mbway.svg" title="mbway" height="36" />
                    <p>{{ Config::get('app.donate.mbway') }}</p>
                </div>
            </li>
            <li class="paypal">
                <a href="https://{{ Config::get('app.donate.paypal') }}" target="_blank" rel="noopener">
                    <img src="/img/svg/paypal.svg" title="paypal" height="36" />
                    <p>{{ Config::get('app.donate.paypal') }}</p>
                </a>
            </li>
            <li class="selectable">
                <div>
                    <img src="/img/svg/bank.svg" title="bank" height="36" />
                    <p>{{ Config::get('app.donate.iban') }}</p>
                </div>
            </li>
        </ul>
    </div>

    {{--
    @php
    $files = glob("img/error/*.jpg");
    $img = $files[array_rand($files)];
    @endphp
    --}}
    <div class="image" style="background-image: url('/img/error/photo-1518843025960-d60217f226f5.jpg');"></div>
</div>

@endsection
