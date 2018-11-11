@extends('layouts.app')

@section('content')
<div id="animals">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['animals_title'] }}</h1>
                        <p>{!! $page['animals_text'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                <picture>
                    <source srcset="img/animals.webp" type="image/webp"/>
                    <source srcset="img/animals.jpg" type="image/jpeg"/>
                    <img src="img/animals.jpg" alt="Association"/>
                </picture>
            </div>
        </div>
    </div>

    @component('components.urgent-help', ['page' => $page, 'processes' => $processes])
    @endcomponent

    <div class="container isotope animals">
        <div class="options">
            <a onclick="app.onAnimalsCategorySelect(this)" option="adoption" class="lined active">{{ __("waiting_adoption") }}</a>
            <a onclick="app.onAnimalsCategorySelect(this)" option="godfather" class="lined">{{ __("waiting_godfather") }}</a>
        </div>

        <div class="selects">
            <select class="toggle adoption" onchange="app.searchAnimals()">
                <option value="0" selected>{{ __("All Districts") }}</option>
                @foreach($districts['adoption'] as $adotion)
                <option value="{{ $adotion->id }}">{{ $adotion->name }}</option>
                @endforeach
            </select>

            <select class="toggle godfather hide" onchange="app.searchAnimals()">
                <option value="0" selected>{{ __("All Districts") }}</option>
                @foreach($districts['godfather'] as $godfather)
                <option value="{{ $godfather->id }}">{{ $godfather->name }}</option>
                @endforeach
            </select>

            <select class="specie" onchange="app.searchAnimals()">
                <option value="0" selected>{{ __("All Species") }}</option>
                @foreach($species as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <p class="status results-empty">{{ __("No results") }}...</p>
        <p class="status results-loading">{{ __("Loading results") }}...</p>

        <template id="animal-box-template">
            <div class="box active" onclick="" option="" animal="0">
                <div class="image"><img src="" /></div>
                <h1 class="name"></h1>
                <div class="location"></div>
                <div class="date"></div>
            </div>
        </template>
    </div>

    @component('components.banner.risk')
    @endcomponent

    <script id="onLoad">if(typeof app !== 'undefined') app.searchAnimals()</script>
@endsection
