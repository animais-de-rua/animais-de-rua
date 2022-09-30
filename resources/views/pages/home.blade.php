@extends('layouts.app')

@section('content')
<div page-title="{{ config('app.name') }}"></div>

<div id="home">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['association_title'] }}</h1>
                        <p>{!! $page['association_text'] !!}</p>
                        <a href="/association" class="link lined">{{ $page['association_link'] }}</a>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                <picture>
                    <source srcset="/img/home.webp" type="image/webp" />
                    <source srcset="/img/home.jpg" type="image/jpeg" />
                    <img src="/img/home.jpg" alt="Home" width="595" height="468" />
                </picture>
            </div>
        </div>
    </div>

    <div style="max-width: 1210px; margin: auto;">
        <img src="/img/ffe-banner.png" />

        <div style="margin: 2rem 0;text-align: center;font-weight: 500;">
            <p>Hoje é um grande dia para a causa animal!</p>
            <p>Lançamos a Iniciativa dos Cidadão Europeus
                <a href="https://www.instagram.com/explore/tags/furfreeeurope/" class="lined">#FurFreeEurope</a>,
                pedindo à Europa
                que:
            </p>
            <p>
                🦊🚫 Proíba as fábricas de peles<br />
                🛍🚫 Proíba os produtos de fábricas de peles no mercado europeu
            </p>
            <p>
                Esta é a nossa oportunidade de fazer história e acabar com as perigosas, antiéticas e insustentáveis
                fábricas de peles.<br />
                ✍➡ Assina aqui para que possamos atingir este objetivo juntos!
            </p>
            <p>Mais informações em <a href="https://furfreeeurope.eu" class="lined">furfreeeurope.eu</a></p>
        </div>

        <script src="https://sign.furfreeeurope.eu/d/fur_free_europe/animais_de_rua-1"></script>
    </div>

    @component('components.urgent-help', ['page' => $page, 'processes' => $processes])
    @endcomponent

    <div class="container campaigns">
        <div class="flex-slider swipeable" auto-scroll="5000">
            <ul class="touchable">
                @foreach($campaigns as $campaign)
                <li>
                    <div class="slide">
                        <div>
                            <img src="/uploads/{{ $campaign->image }}" alt="{{ $campaign->name }}" loading="lazy" width="585" height="440" />
                        </div>
                        <div>
                            <blockquote>{{ __("campaigns") }}</blockquote>
                            <h1>{{ $campaign->name }}</h1>
                            <p>{{ $campaign->introduction }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <ul class="dots">
                @foreach($campaigns as $i => $campaign)
                <li class="{{ $i == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="container products three-slider">
        <h1>{{ $page['products_title'] }}</h1>
        <p>{{ $page['products_subtitle'] }}</p>
        <div class="flex-slider swipeable">
            @php
            $pages = ceil(count($products) / 3);
            @endphp
            <ul class="touchable">
                @for($i=0; $i<$pages; $i++)
                <li>
                    <div class="slide">
                        @for($j=0; $j<3 && isset($products[$j + $i * 3]); $j++)
                        @php
                            $product = $products[$j + $i * 3];
                        @endphp
                        <div class="card" onclick="app.track('ViewContent', {'product': '{{ $product->name }}'})">
                            <a href="{{ $product->url }}">
                                <div class="image">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy" width="366" height="190" />
                                </div>
                                <div>
                                    <h1>{{ $product->name }}</h1>
                                    <div class="price">{{ number_format($product->price, 2, '.', '') }}€</div>
                                </div>
                            </a>
                        </div>
                        @endfor
                    </div>
                </li>
                @endfor
            </ul>
            <ul class="dots">
                @for($i=0; $i<$pages; $i++)
                <li class="{{ $i == 0 ? 'active' : '' }}"></li>
                @endfor
            </ul>
        </div>
    </div>


    @component('components.banner.risk')
    @endcomponent

    <div class="container how-to-help">
        <h2>{{ $page["help_title"] }}</h2>
        <div class="row row-wrap header">
            @foreach(['volunteer', 'friend', 'godfather', 'donate'] as $link)
            <a href="/help#{{ $link }}" class="column box link">
                <h3>{{ $page["help_{$link}_title"] }}</h3>
                <p>{{ $page["help_{$link}_text"] }}</p>
                <div class="icon icon-arrow"></div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
