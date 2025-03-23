@extends('layouts.app')

@section('title', $title)

@section('content')
<div page-title="{{ $title }}"></div>

<div id="petsitting">
    <div class="container">
        <div class="row row-wrap header">
            <div class="column column-50">
                <div class="flex-vertical-align">
                    <div class="info-text">
                        <h1 class="label-title">{{ $page['petsitting_title'] }}</h1>
                        <p>{!! $page['petsitting_text'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'petsitting', 'width' => 595, 'height' => 468])
                @endcomponent
            </div>
        </div>
    </div>

    <div class="content">
        <div class="petsitters">
            <h3 class="heading">{{ __("Get to know our Petsitting team!") }}</h3>
            <div class="cards">
                @foreach($petsitters as $petsitter)
                    <div class="card">
                        <div class="card-image">
                            <img src="{{ $petsitter->petsitting_image }}" alt="profile-image">
                            <div class="mask"></div>
                            <div class="person">
                                <span class="name">{{ $petsitter->name }}</span>
                                <span class="role">
                                    Petsitting {{ $petsitter->petsitting_role === 'Both' ? __('dog') . ' ' . __('and') . ' ' . __('cat') : ucfirst(__(strtolower($petsitter->petsitting_role))) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <span class="city">
                                @foreach ($petsitter->headquarters as $headquarter)
                                    {{ $headquarter->name }}{{ $loop->last ? '' : ', ' }}
                                @endforeach
                            </span>
                            <p class="description">
                                {{ $petsitter->petsitting_description }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-open">
            <button onclick="return modal.openPetsittingForm('petsitting')" class="form-button">
                {{ __("Submit your request") }}
            </button>
        </div>
    </div>
</div>
@endsection
