@extends('layouts.app')

@section('content')
<div id="association">
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
                    <source srcset="img/association.webp" type="image/webp"/>
                    <source srcset="img/association.jpg" type="image/jpeg"/>
                    <img src="img/association.jpg" alt="Association"/>
                </picture>
            </div>
        </div>
    </div>

    <div class="container whoweare">
        <div class="row row-wrap">
            <div class="column column-50 column-offset-25">
                <h1 class="label-title">{{ $page['whoweare_title'] }}</h1>
                <p>{!! $page['whoweare_text'] !!}</p>
                <hr />
                <h2>{{ __("web.association.report") }}</h2>
                <ul class="arrow-links">
                    <li><a href="{{ $page['report_link'] }}">{{ __("web.association.report_year", ['year' => $page['report_year']]) }} <span class="icon icon-arrow"></span></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row row-wrap">
            <div class="column column-50">
                <picture>
                    <source srcset="img/association01.webp" type="image/webp"/>
                    <source srcset="img/association01.jpg" type="image/jpeg"/>
                    <img src="img/association01.jpg" alt="Association"/>
                </picture>
            </div>
            <div class="column column-50">
                <picture>
                    <source srcset="img/association02.webp" type="image/webp"/>
                    <source srcset="img/association02.jpg" type="image/jpeg"/>
                    <img src="img/association02.jpg" alt="Association"/>
                </picture>
            </div>
        </div>
    </div>

    <div class="container acting">
        <div class="row row-wrap">
            <div class="column column-50 column-offset-25">
                <div class="accordion open">
                    <h1>{{ $page['act_title'] }}</h1>
                    <div class="slider dash-list">
                        <div>{!! $page['act_text'] !!}</div>
                    </div>
                </div>
                <div class="accordion">
                    <h1>{{ $page['program_title'] }}</h1>
                    <div class="slider dash-list">
                        <div>{!! $page['program_text'] !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container vision">
        <div class="row row-wrap">
            <div class="column column-50">
                <div class="white-box" style="margin-top: 0">
                    <h1>{{ $page['vision_title'] }}</h1>
                    <div>{!! $page['vision_text'] !!}</div>
                </div>
                <div class="white-box">
                    <h1>{{ $page['mission_title'] }}</h1>
                    <div>{!! $page['mission_text'] !!}</div>
                </div>
            </div>
            <div class="column column-50">
                <div class="white-box" style="height: 100%">
                    <h1>{{ $page['values_title'] }}</h1>
                    <div>{!! $page['values_text'] !!}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container where">
        <h1>{{ $page['where_title'] }}</h1>
        <div>{!! $page['where_text'] !!}</div>

        <div class="headquarters">
        @foreach($headquarters as $headquarter)
            <a href="mailto:{{ $headquarter->mail }}">
                <h1>animais de rua</h1>
                <p>{{ $headquarter->name }}</p>
            </a>
        @endforeach
        </div>
    </div>

    @component('components.banner.ced')
    @endcomponent

</div>
@endsection
