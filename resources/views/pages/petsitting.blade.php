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
        <div class="form-container">
            <div class="inner-container">
                <div class="title">
                    <h3>Submeta o seu pedido</h3>
                </div>
                <form action="/form/petsitting" onsubmit="">
                    @csrf
                    <div class="body">
                        <div class="dates">
                            <div class="date-container">
                                <label for="initial-date">Data de início</label>
                                <input
                                    id="initial-date"
                                    type="date"
                                    value=""
                                    min="<?= date('Y-m-d'); ?>"
                                    onchange="handleDateChange(event)"
                                />
                            </div>
                            <div class="date-container">
                                <label for="final-date">Data de fim</label>
                                <input
                                    id="final-date"
                                    type="date"
                                    value=""
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="address">
                            <label for="address">Morada</label>
                            <input type="text" id="address" placeholder="Indique a sua morada" maxlength="35" />
                        </div>
                        <div class="animal">
                            <div class="animal-container">
                                <span>Animal</span>
                                <div class="options">
                                    <div class="option">
                                        <input type="checkbox" id="dog">
                                        <label for="dog">Cão</label>
                                    </div>
                                    <div class="option">
                                        <input type="checkbox" id="cat">
                                        <label for="cat">Gato</label>
                                    </div>
                                </div>
                            </div>
                            <div class="animal-container">
                                <label for="number-of-animals">Nº de animais</label>
                                <input type="number" id="number-of-animals" placeholder="0" max="2" />
                            </div>
                        </div>
                        <div class="daily-work">
                            <div class="visits">
                                <span>Visitas diárias</span>
                                <div class="options">
                                    <label>
                                        <input type="radio" name="visit-number" value="1" />
                                        <span>1</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="visit-number" value="2" />
                                        <span>2</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="visit-number" value="3" />
                                        <span>3</span>
                                    </label>
                                </div>
                            </div>
                            <div class="walk">
                                <span>Passeio?</span>
                                <div class="options">
                                    <div class="radio-container">
                                        <input type="radio" id="yes" name="walk-options" value=true>
                                        <label for="yes">Sim</label>
                                    </div>
                                    <div class="radio-container">
                                        <input type="radio" id="no" name="walk-options" value=false checked>
                                        <label for="no">Não</label>
                                    </div>
                                </div>
                            </div>
                            <div class="daily-walks">
                                <span>Passeios diários</span>
                                <div class="options">
                                    <label>
                                        <input type="radio" name="walk-number" value="1" />
                                        <span>1</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="walk-number" value="2" />
                                        <span>2</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="walk-number" value="3" />
                                        <span>3</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="details">
                            <div class="details-area">
                                <label for="food">Alimentação</label>
                                <textarea id="food" rows="5" cols="86" placeholder="Alguma alimentação especial a ter em conta?"></textarea>
                            </div>
                            <div class="details-area">
                                <label for="medication">Medicação</label>
                                <textarea id="medication" rows="5" cols="86" placeholder="Alguma medicação que o seu animal precise de tomar?"></textarea>
                            </div>
                            <div class="details-area">
                                <label for="others">Outros detalhes</label>
                                <textarea id="others" rows="5" cols="86" placeholder="Outros detalhes que ache necessário indicar"></textarea>
                            </div>
                        </div>
                        <div class="prices">
                            <span class="prices-title">Preços</span>
                            <ul class="options">
                                <li>Visita gato: 12,50 €</li>
                                <li>
                                    Visita cão: 15,50 €
                                    <ul class="walks">
                                        <li><span>Com 1 passeio: + 15,50 €</span></li>
                                        <li><span>Com 2 passeio: + 20 €</span></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button type="submit" class="submit">
                        {{ __("Send") }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function handleDateChange(event) {
      const finalDateDatePicker = document.getElementById('final-date');

      if (event.target.value) {
        const finalDate = new Date(event.target.value);
        finalDate.setDate(finalDate.getDate() + 7);

        finalDateDatePicker.min = event.target.value;
        finalDateDatePicker.max = finalDate.toISOString().split('T')[0]
        finalDateDatePicker.disabled = false;
      }
    }
</script>
