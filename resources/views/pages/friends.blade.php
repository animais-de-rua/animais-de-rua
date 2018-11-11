@extends('layouts.app')

@section('content')
<div id="friends">
    <div class="container">
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
                <picture>
                    <source srcset="img/friend.webp" type="image/webp"/>
                    <source srcset="img/friend.jpg" type="image/jpeg"/>
                    <img src="img/friend.jpg" alt="Help"/>
                </picture>
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
                    <input type="hidden" name="email" value="{{ env('PAYPAL') }}">
                    <input type="hidden" name="hosted_button_id" value="{{ env('PAYPAL_ID') }}">
                    <input type="hidden" name="on0" value="{{ ucfirst(__('friend card')) }}">
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
        <p class="empty">{{ __("No results") }}...</p>
        @foreach($partners['list'] as $partner)
        <div class="box active" categories="{{ join(' ', $partner->categories) }}" territories="{{ join(' ', $partner->territories) }}">
            <div class="image" title="{{ $partner->name }}">
                <img src="/uploads/{{ $partner->image }}" alt="{{ $partner->name }}" />
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
                @if($partner->website)
                <p><a target="_blank" href="{{ $partner->website }}">{{ __("Website") }}</a></p>
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
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
