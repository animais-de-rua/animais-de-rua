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
        <div class="volunteers">
            <h3 class="heading">Conheça a nossa equipa de Petsitting!</h3>
            <div class="cards">
                @foreach($volunteers as $volunteer)
                    <div class="card">
                        <div class="card-image">
                            <img src="{{ $volunteer->petsitting_image }}" alt="profile-image">
                            <div class="mask"></div>
                            <div class="person">
                                <span class="name">{{ $volunteer->name }}</span>
                                <span class="role">
                                    Petsitting {{ $volunteer->petsitting_role === 'Both' ? __('dog') . ' ' . __('and') . ' ' . __('cat') : __($volunteer->petsitting_role) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <span class="city">
                                @foreach ($volunteer->headquarters as $headquarter)
                                    {{ $headquarter->name }}{{ $loop->last ? '' : ', ' }}
                                @endforeach
                            </span>
                            <p class="description">
                                {{ $volunteer->petsitting_description }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-open">
            <button onclick="return modal.openPetsittingForm('petsitting')" class="form-button">
                Submeta o seu pedido
            </button>
        </div>
    </div>
</div>
@endsection
