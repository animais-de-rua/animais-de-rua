@extends('layouts.app')

@section('title', $title)

@section('content')
<div page-title="{{ $title }}"></div>

<div id="friends">
    <div class="container">
        @if($subscribed)
        <div class="friend-alert">{{ __('web.subscribed') }}</div>
        @endif

        @if (session('logout'))
        <div class="friend-alert">{{ __('web.friend_card_login.logout_success') }}</div>
        @endif

        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1>{{ $page['friend_title'] }}</h1>
                        <p>{!! $page['friend_text'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'friend'])
                @endcomponent
            </div>
        </div>
    </div>

    <div class="container friendcard">
        <div class="row row-wrap">
            <div class="column column-50 column-offset-25">
                <h1>{{ $page['card_title'] }}</h1>
                <p>{!! $page['card_text'] !!}</p>
            </div>
        </div>
    </div>

    <div class="container modalities">
        <div class="row row-wrap">
            <div class="column">
                <h1>{{ $page['modalities_title'] }}</h1>
            </div>
            <div class="column column-80 column-offset-10">
                <form action="https://www.paypal.com/yt/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="charset" value="utf-8">
                    <input type="hidden" name="email" value="{{ Config::get("app.paypal") }}">
                    <input type="hidden" name="hosted_button_id" value="{{ Config::get("app.paypal_id") }}">
                    <input type="hidden" name="on0" value="Cartão Amigo da Animais de Rua">
                    <select name="os0">
                        @foreach($modalities as $modality)
                        <option {{ $loop->first ? "selected" : "" }} value="{{ $modality->paypal_code }}">{{ $modality->paypal_code }} : €{{ $modality->amount }},00 EUR - {{ ucfirst(__($modality->type)) }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="currency_code" value="EUR">
                </form>
                <div class="row row-wrap">
                    @foreach($modalities as $modality)
                    <div class="column column-33">
                        <div class="box" onclick="app.onModalitiesClick(this)">
                            <p><b>{{ $modality->name }}</b> - {{ $modality->description }}</p>
                            <p class="price">{{ $modality->amount }}€ {{ __($modality->type) }}</p>
                            <div class="slider">
                                <p>{{ __("Select") }} <span class="icon icon-arrow"></span></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="container advantages">
        <div class="row row-wrap">
            <div class="column">
                <h1>{{ $page['advantages_title'] }}</h1>
            </div>
        </div>

        <div class="row">
            <iframe src="{{ $page['advantages_map'] }}"></iframe>
        </div>
    </div>

    <div class="container card-login">

        @if (session()->has('login'))
        <div class="friend-alert {{ session('login') ? 'success' : 'error' }}">{{ session('login') ? __('web.friend_card_login.login_success') : __('web.friend_card_login.login_error') }}</div>
        @endif

        <h1>{{ __('web.friend_card_login.title') }}</h1>

        @if(backpack_user())
            <div class="box">
                <span>{!! __('web.friend_card_login.welcome', ['name' => '<strong>'.backpack_user()->name.'</strong>']) !!}</span>
                @if($hasAccess)
                <p>{{ __('web.friend_card_login.messages.1') }}</p>
                @else
                <p>{{ __('web.friend_card_login.messages.2') }}</p>
                @endif
                <a href="{{ url('/logout') }}"><button class="btn">{{ trans('backpack::base.logout') }}</button></a>
            </div>
        @else
            <p>{{ __('web.friend_card_login.messages.3') }}</p>

            <div class="box">
                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    <label>{{ trans('backpack::base.email_address') }}</label>
                    <input name="email" type="email" />
                    <label>{{ trans('backpack::base.password') }}</label>
                    <input name="password" type="password" />
                    <br />
                    <input type="submit" class="btn" value="{{ trans('backpack::base.login') }}" />
                </form>
                <p class="password-reset"><a href="{{ url('/admin/password/reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></p>
            </div>
        @endif
    </div>

    <div class="container isotope friends">
        <div class="selects">
            <select type="territories">
                <option value="" selected>{{ __("All Districts") }}</option>
                @foreach($partners['territories'] as $territory)
                <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                @endforeach
            </select>
            <select type="categories">
                <option value="" selected>{{ __("All Categories") }}</option>
                @foreach($partners['categories'] as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <p class="empty hide">{{ __("No results") }}...</p>
        @foreach($partners['list'] as $partner)
        <div class="box active" categories="{{ join(' ', $partner->categories) }}" territories="{{ join(' ', $partner->territories) }}">
            <div class="image" title="{{ $partner->name }}">
                @if($partner->url)
                <a target="_blank" href="{{ $partner->url }}">
                @endif
                <img src="/uploads/{{ $partner->image }}" alt="{{ $partner->name }}" loading="lazy"/>
                @if($partner->url)
                </a>
                @endif
            </div>
            <div class="contact">
                <h1>{{ __("Contacts") }}</h1>
                <p><a class="ellipsis" target="_blank" href="mailto:{{ $partner->email }}">{{ $partner->email }}</a></p>
                @if($partner->phone1)
                <p><a target="_blank" href="tel:{{ preg_replace( '/[^0-9\+]/', '', $partner->phone1) }}">{{ preg_replace( '/\+[0-9]{1,3}\s?/', '', $partner->phone1) }}</a> <span class="details">{{ $partner->phone1_info }}</span></p>
                @endif
                @if($partner->phone2)
                <p><a target="_blank" href="tel:{{ preg_replace( '/[^0-9\+]/', '', $partner->phone2) }}">{{ preg_replace( '/\+[0-9]{1,3}\s?/', '', $partner->phone2) }}</a> <span class="details">{{ $partner->phone2_info }}</span></p>
                @endif
                @if($partner->facebook)
                <p><a target="_blank" href="{{ $partner->facebook }}">{{ __("Facebook") }}</a></p>
                @endif
                @if($partner->url)
                <p><a class="ellipsis" target="_blank" href="{{ $partner->url }}">{{ preg_replace("/(https?:\/\/)|(\/$)|(www.)/", "", $partner->url) }}</a></p>
                @endif
                @if($partner->address)
                <hr />
                <a target="_blank" href="https://maps.google.com/?q={{ $partner->address }}"><p class="address">{{ $partner->address }} <span class="details">{{ $partner->address_info }}</span></p></a>
                @endif
            </div>
            <div class="benefit">
                @if($partner->benefit)
                <h1>{{ __("Discounts") }}</h1>
                <p>{{ $partner->benefit }}</p>
                @endif

                {{-- Promo code for users with friend card --}}
                @if($hasAccess && $partner->promo_code)
                <hr />
                <i><h1>{{ __("Promo Code") }}</h1></i>
                <i><p>{{ $partner->promo_code }}</p></i>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if (session()->has('login'))
    <script>window.scrollTo(0, document.querySelector('.card-login').offsetTop - 20);</script>
    @endif

</div>
@endsection
