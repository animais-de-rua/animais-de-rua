@extends('layouts.app')

@section('content')
<div id="help">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['help_title'] }}</h1>
                        <p>{!! $page['help_text'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'help'])
                @endcomponent
            </div>
        </div>
    </div>
    <div class="container help">
        <div class="row row-wrap">
            <div class="column column-50">
                <div class="white-box">
                    <h2>{{ $page['volunteer_title'] }}</h2>
                    <div class="text">{!! $page['volunteer_text'] !!}</div>
                    <a onclick="return modal.open('volunteer')" class="lined">{{ $page['volunteer_link'] }}</a>
                </div>
                <div class="white-box">
                    <h2>{{ $page['friend_title'] }}</h2>
                    <div class="text">{!! $page['friend_text'] !!}</div>
                    <a href="/friends" class="link lined">{{ $page['friend_link'] }}</a>
                </div>
                @component('components.picture', ['image' => 'help02', 'lazy' => true])
                @endcomponent
                <div class="white-box">
                    <h2>{{ $page['call_title'] }}</h2>
                    <div class="text">{!! $page['call_text'] !!}</div>
                </div>
                <div class="white-box">
                    <h2>{{ $page['associate_title'] }}</h2>
                    <div class="text">{!! $page['associate_text'] !!}</div>
                </div>
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'help01'])
                @endcomponent
                <div class="white-box">
                    <h2>{{ $page['adopt_title'] }}</h2>
                    <div class="text">{!! $page['adopt_text'] !!}</div>
                    <a href="/animals" class="link lined">{{ $page['adopt_link'] }}</a>
                </div>
                <div class="white-box">
                    <h2>{{ $page['store_title'] }}</h2>
                    <div class="text">{!! $page['store_text'] !!}</div>
                    <a href="/store" class="lined">{{ $page['store_link'] }}</a>
                </div>
                <div class="white-box">
                    <h2>{{ $page['donate_title'] }}</h2>
                    <div class="text dash-list">{!! $page['donate_text'] !!}</div>
                    <form name="_xclick" action="https://www.paypal.com/yt/cgi-bin/webscr" method="post" target="_blank">
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="{{ Config::get("app.paypal") }}">
                        <input type="hidden" name="item_name" value="{{ __("Donate") }}">
                        <input type="hidden" name="currency_code" value="EUR">
                        <button class="btn" type="submit" src="" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">{{ $page['donate_link'] }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
