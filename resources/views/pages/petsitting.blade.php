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
                        <h1 class="label-title">{{ $page['partners_title'] }}</h1>
                        <p>{!! $page['partners_text'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="column column-50">
                @component('components.picture', ['image' => 'partners', 'width' => 595, 'height' => 468])
                @endcomponent
            </div>
        </div>
    </div>

    <div class="content">
        <div class="volunteers">
            <h3 class="heading">Conheça a nossa equipa de Petsitting!</h3>
            <div class="cards">
                <div class="card">
                    <div class="card-image">
                        <img src="img/animals.jpg" alt="profile-image">
                        <div class="mask"></div>
                        <div class="person">
                            <span class="name">José Ribeiro</span>
                            <span class="role">Voluntário</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="city">Paredes de Coura</span>
                        <p class="description">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque beatae, delectus dicta eius et eum fugiat neque nihil omnis pariatur possimus quaerat quibusdam quis repellendus sed sit, suscipit totam.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">
                        <img src="/img/association.jpg" alt="profile-image">
                        <div class="mask"></div>
                        <div class="person">
                            <span class="name">Isabel Maia</span>
                            <span class="role">Voluntário</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="city">Cascais</span>
                        <p class="description">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque beatae, delectus dicta eius et eum fugiat neque nihil omnis pariatur possimus quaerat quibusdam quis repellendus sed sit, suscipit totam.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">
                        <img src="/img/association01.jpg" alt="profile-image">
                        <div class="mask"></div>
                        <div class="person">
                            <span class="name">Anna Pinheiro</span>
                            <span class="role">Voluntário</span>

                        </div>
                    </div>
                    <div class="card-body">
                        <span class="city">Lisboa</span>
                        <p class="description">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque beatae, delectus dicta eius et eum fugiat neque nihil omnis pariatur possimus quaerat quibusdam quis repellendus sed sit, suscipit totam.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">
                        <img src="/img/association02.jpg" alt="profile-image">
                        <div class="mask"></div>
                        <div class="person">
                            <span class="name">Filipe Lourenço</span>
                            <span class="role">Voluntário</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="city">Lisboa</span>
                        <p class="description">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque beatae, delectus dicta eius et eum fugiat neque nihil omnis pariatur possimus quaerat quibusdam quis repellendus sed sit, suscipit totam.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">
                        <img src="/img/ced01.jpg" alt="profile-image">
                        <div class="mask"></div>
                        <div class="person">
                            <span class="name">Tatiana Lopes</span>
                            <span class="role">Voluntário</span>

                        </div>
                    </div>
                    <div class="card-body">
                        <span class="city">Paredes de Coura</span>
                        <p class="description">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque beatae, delectus dicta eius et eum fugiat neque nihil omnis pariatur possimus quaerat quibusdam quis repellendus sed sit, suscipit totam.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">
                        <img src="/img/friend.jpg" alt="profile-image">
                        <div class="mask"></div>
                        <div class="person">
                            <span class="name">Neuza Pires</span>
                            <span class="role">Voluntário</span>

                        </div>
                    </div>
                    <div class="card-body">
                        <span class="city">Porto</span>
                        <p class="description">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque beatae, delectus dicta eius et eum fugiat neque nihil omnis pariatur possimus quaerat quibusdam quis repellendus sed sit, suscipit totam.
                        </p>
                    </div>
                </div>
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
