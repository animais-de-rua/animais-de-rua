@if(\Session::has('form'))
@section('script')
<script>modal.open('{{ \Session::pull('form') }}');</script>
@endsection
@endif

<div id="forms" style="display: none;">
    <div class="container">
        <div class="nav">
            <i class="icon icon-logo"></i>
            <i class="icon icon-close" onclick="modal.close()"></i>
        </div>
        <div class="row scrollable">
            <div class="column column-80 column-offset-10">
                <div class="sending">
                    <h1>{{ __("Sending") }} ...</h1>
                </div>
                <div class="success">
                    <h1>{{ __("Sucess") }}</h1>
                    <p></p>
                </div>
                <div class="content">
                    <div class="header hide">
                        <h1 class="title">{{ __("web.forms.title") }}</h1>
                        <h2>{{ __("web.forms.interest") }}</h2>

                        <div class="row row-wrap">
                            <div class="column column-50">
                                <select class="options" onchange="modal.onCategorySelect(this)">
                                    <option value="volunteer">{{ __("web.forms.options.volunteer") }}</option>
                                    <option value="contact">{{ __("web.forms.options.contact") }}</option>
                                    <option value="apply">{{ __("web.forms.options.apply") }}</option>
                                    <option value="training">{{ __("web.forms.options.training") }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="errors hide"></div>

                    <div class="godfather hide">
                        <h1></h1>
                    </div>

                    <div class="address-selects hide">
                        <select required name="district" autocomplete="address-level1" class="dark empty" onchange="modal.onDistrictSelect(this)">
                            <option value="" disabled selected>{{ ucfirst(__("district")) }}</option>
                            @foreach($form_all_territories[0] as $district)
                            <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                            @endforeach
                        </select>
                        <select required name="county" autocomplete="address-level2" class="dark empty" onchange="modal.checkEmptySelect(this)">
                            <option value="" disabled selected>{{ ucfirst(__("county")) }}</option>
                            @foreach($form_all_territories[1] as $county)
                            <option value="{{ $county['id'] }}" parent="{{ $county['parent_id'] }}" class="hide">{{ $county['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- FORM Voluntary --}}
                    <form action="/form/volunteer" onsubmit="return modal.submit(this)">
                        <div class="form volunteer row row-wrap hide">
                            <div class="column column-50">
                                @csrf
                                <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                                <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                                <input type="tel" pattern="[0-9\s]{9,16}" title="{{ __('Valid phone number') }}" required name="phone" placeholder="{{ __("Phone") }}" />
                                <input type="number" min="15" max="99" required name="age" placeholder="{{ __("Age") }}" />
                                <input type="text" required name="job" placeholder="{{ __("Job") }}" />
                                <div class="address-selects"></div>
                                <input type="text" required name="schedule" class="last-child" placeholder="{{ __("Availability Schedule") }}" />
                            </div>
                            <div class="column column-50 checkbox interests">
                                <h1>{{ __("web.forms.interests.title") }}</h1>

                                @for ($i = 1; $i <= 9; $i++)
                                <div>
                                    <input type="checkbox" id="interest{{ $i }}" name="interest[]" value="{{ $i }}" />
                                    <label for="interest{{ $i }}">{{ __("web.forms.interests.$i") }}</label>
                                </div>
                                @endfor

                                <textarea required name="observations" style="height: 168px; margin: 10px 0 6px;" placeholder="{{ __("Observations") }}"></textarea>
                            </div>
                            <div class="submit column column-50 column-offset-50 checkbox">
                                <div>
                                    <input type="checkbox" name="newsletter" value="newsletter" id="newsletter1" />
                                    <label for="newsletter1">{{ __("web.forms.newsletter") }}</label>
                                </div>
                                <input type="submit" value="{{ __("Send") }}" />
                            </div>
                        </div>
                    </form>

                    {{-- FORM Contact --}}
                    <form action="/form/contact" onsubmit="return modal.submit(this)">
                        <div class="form contact row row-wrap hide">
                            <div class="column column-50">
                                @csrf
                                <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                                <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                                <input type="tel" pattern="[0-9\s]{9,16}" title="{{ __('Valid phone number') }}" required name="phone" placeholder="{{ __("Phone") }}" />
                                <div class="address-selects"></div>
                            </div>
                            <div class="column column-50">
                                <input type="text" required name="subject" placeholder="{{ __("Subject") }}" />
                                <textarea required name="observations" style="height: calc(100% - 54px)" placeholder="{{ __("Observations") }}"></textarea>
                            </div>
                            <div class="submit column column-50 column-offset-50 checkbox">
                                <div>
                                    <input type="checkbox" name="newsletter" value="newsletter" id="newsletter2" />
                                    <label for="newsletter2">{{ __("web.forms.newsletter") }}</label>
                                </div>
                                <input type="submit" value="{{ __("Send") }}" />
                            </div>
                        </div>
                    </form>

                    {{-- FORM Training --}}
                    <form action="/form/training" onsubmit="return modal.submit(this)">
                        <div class="form training row row-wrap hide">
                            <div class="column column-50">
                                @csrf
                                <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                                <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                                <input type="tel" pattern="[0-9\s]{9,16}" title="{{ __('Valid phone number') }}" required name="phone" placeholder="{{ __("Phone") }}" />
                                <div class="address-selects"></div>
                                <select required name="theme" class="dark last-child empty" onchange="modal.checkEmptySelect(this)">
                                    <option value="" disabled selected>{{ ucfirst(__("Themes of interest")) }}</option>
                                    @foreach(\App\Helpers\EnumHelper::get('forms.themes') as $key => $value)
                                    <option value="{{ $key }}">{{ ucfirst(__($value)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="column column-50">
                                <textarea required name="observations" style="height: 100%" placeholder="{{ __("Observations") }}"></textarea>
                            </div>
                            <div class="submit column column-50 column-offset-50 checkbox">
                                <div>
                                    <input type="checkbox" name="newsletter" value="newsletter" id="newsletter3" />
                                    <label for="newsletter3">{{ __("web.forms.newsletter") }}</label>
                                </div>
                                <input type="submit" value="{{ __("Send") }}" />
                            </div>
                        </div>
                    </form>

                    {{-- FORM Sterilize --}}
                    <form action="/form/apply" enctype="multipart/form-data" onsubmit="return modal.submit(this)">
                        <div class="form apply row row-wrap hide">
                            <div class="column column-50">
                                @csrf
                                <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                                <div class="row row-wrap">
                                    <div class="column column-50">
                                        <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                                    </div>
                                    <div class="column column-50">
                                        <input type="tel" pattern="[0-9\s]{9,16}" title="{{ __('Valid phone number') }}" required name="phone" placeholder="{{ __("Phone") }}" />
                                    </div>
                                </div>
                                <input type="text" required name="process" placeholder="{{ __("Process name") }}" />
                                <div class="row row-wrap">
                                    <div class="column column-50">
                                        <input type="number" required name="animals" min="0" max="99" placeholder="{{ __("Number of Animals") }}" />
                                    </div>
                                    <div class="column column-50">
                                        <select class="dark empty" required name="specie" onchange="modal.checkEmptySelect(this)">
                                            <option value="" disabled selected>{{ __("Specie") }}</option>
                                            @foreach(\App\Helpers\EnumHelper::get('process.specie') as $key => $value)
                                            <option value="{{ $key }}">{{ ucfirst(__($value)) }}</option>
                                            @endforeach
                                            <option value="other">{{ ucfirst(__('other')) }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row row-wrap">
                                    <div class="column column-50">
                                        <input type="text" required name="address" placeholder="{{ __("Address") }}" />
                                    </div>
                                    <div class="column column-50">
                                        <input type="text" required pattern="\d{4}-\d{3}" title="0000-000" name="postalcode" placeholder="{{ __("Postal Code") }}" />
                                    </div>
                                </div>
                                <select required name="district" autocomplete="address-level1" class="dark empty" onchange="modal.onDistrictSelect(this)">
                                    <option value="" disabled selected>{{ ucfirst(__("district")) }}</option>
                                    @foreach($form_acting_territories[0] as $district)
                                    <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                    @endforeach
                                </select>
                                <select required name="county" autocomplete="address-level2" class="dark empty" onchange="modal.onDistrictSelect(this)">
                                    <option value="" disabled selected>{{ ucfirst(__("county")) }}</option>
                                    @foreach($form_acting_territories[1] as $county)
                                    <option value="{{ $county['id'] }}" parent="{{ $county['parent_id'] }}" class="hide">{{ $county['name'] }}</option>
                                    @endforeach
                                </select>
                                <select required name="parish" autocomplete="address-level3" class="dark empty" onchange="modal.checkEmptySelect(this)" style="margin-bottom: 2px;">
                                    <option value="" disabled selected>{{ ucfirst(__("parish")) }}</option>
                                    @foreach($form_acting_territories[2] as $parish)
                                    <option value="{{ $parish['id'] }}" parent="{{ $parish['parent_id'] }}" class="hide">{{ $parish['name'] }}</option>
                                    @endforeach
                                </select>
                                <div>
                                    <span style="font-size:1.2rem;color:#fff;font-weight:bold;">{{ __("Select at least 3 pictures.") }}</span>
                                    <div class="row row-wrap">
                                        <div class="column column-50"><input type="file" class="last-child" required name="images[0]" placeholder="{{ __("Photos") }}" /></div>
                                        <div class="column column-50"><input type="file" class="last-child" required name="images[1]" placeholder="{{ __("Photos") }}" /></div>
                                    </div>
                                    <div class="row row-wrap">
                                        <div class="column column-50"><input type="file" class="last-child" required name="images[2]" placeholder="{{ __("Photos") }}" /></div>
                                        <div class="column column-50"><input type="file" class="last-child" name="images[3]" placeholder="{{ __("Photos") }}" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="column column-50 checkbox">
                                <h1>{{ __("web.forms.colab.title") }}</h1>
                                @for ($i = 1; $i <= 4; $i++)
                                <div>
                                    <input type="checkbox" id="colab{{ $i }}" name="colab[]" value="colab.{{ $i }}" />
                                    <label for="colab{{ $i }}">{{ __("web.forms.colab.$i") }}</label>
                                </div>
                                @endfor
                                <textarea required name="observations" style="height: 125px; margin: 10px 0 6px;" placeholder="{{ __("Observations") }}"></textarea>
                                <div class="submit" style="margin: 0; display: block;">
                                    <div class="checkbox">
                                        <input type="checkbox" name="newsletter" value="newsletter" id="newsletter4" />
                                        <label for="newsletter4">{{ __("web.forms.newsletter") }}</label>
                                    </div>
                                    <input type="submit" value="{{ __("Send") }}" />
                                </div>
                            </div>
                            <div class="column notes">
                                <p>Os concelhos disponíveis para selecção neste formulário são os abrangidos pelos núcleos de actuação da Animais de Rua. Caso o concelho que pretende indicar não se encontre nesta lista, sugerimos que contacte as associações locais questionando sobre a possibilidade de implementação de um programa CED.</p>
                            </div>
                        </div>
                    </form>

                    {{-- FORM Godfather --}}
                    <form action="/form/godfather" onsubmit="return modal.submit(this)">
                        <div class="form godfather row row-wrap hide">
                            <div class="column column-50">
                                @csrf
                                <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                                <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                                <input type="tel" pattern="[0-9\s]{9,16}" title="{{ __('Valid phone number') }}" required name="phone" placeholder="{{ __("Phone") }}" />
                                <input type="hidden" name="process_id" />
                                <input type="hidden" name="process_name" />
                                <div class="value">
                                    <h2>{{ __("Godfather value") }}</h2>
                                    <div class="input">
                                        <input type="radio" id="value01" name="value" value="15" />
                                        <label for="value01">15€</label>
                                        <input type="radio" id="value02" name="value" value="30" />
                                        <label for="value02">30€</label>
                                        <input type="number" name="other" placeholder="{{ ucfirst(__("other")) }}" class="last-child" onchange="modal.clearGroup(this)" />
                                    </div>
                                </div>
                            </div>
                            <div class="column column-50 colab">
                                <textarea required name="observations" style="height: calc(100% - 84px); margin: 0 0 6px;" placeholder="{{ __("Observations") }}"></textarea>
                                <div class="submit checkbox" style="margin: 0">
                                    <div>
                                        <input type="checkbox" name="newsletter" value="newsletter" id="newsletter5" />
                                        <label for="newsletter5">{{ __("web.forms.newsletter") }}</label>
                                    </div>
                                    <input type="submit" value="{{ __("Send") }}" style="margin:0;" />
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- FORM Petsitting --}}
                    <form action="/form/petsitting" onsubmit="return modal.submit(this)">
                        @csrf
                        <div class="form petsitting hide">
                            <h1 class="section-title">{{ __("Personal Information") }}</h1>
                            <div class="row">
                                <div class="column column-50">
                                    <label for="first-name" class="form-title">{{ __("First name") }}</label>
                                    <input
                                        id="first-name"
                                        name="first_name"
                                        type="text"
                                        maxlength="35"
                                        required
                                    />
                                </div>
                                <div class="column column-50">
                                    <label for="last-name" class="form-title">{{ __("Last name") }}</label>
                                    <input
                                        id="last-name"
                                        name="last_name"
                                        type="text"
                                        maxlength="35"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="column column-50">
                                    <label for="first-name" class="form-title">{{ __("Email") }}</label>
                                    <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                                </div>
                                <div class="column column-50">
                                    <label for="last-name" class="form-title">{{ __("Phone") }}</label>
                                    <input type="tel" pattern="[0-9\s]{9,16}" title="{{ __('Valid phone number') }}" required name="phone" placeholder="{{ __("Phone") }}" />
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 0.875rem">
                                <div class="column">
                                    <label for="address" class="form-title">{{ __("Address") }}</label>
                                    <input type="text" id="address" name="address" maxlength="54" required />
                                    <div class="row">
                                        <div class="column column-50">
                                            <label for="city" class="form-title">{{ __("City") }}</label>
                                            <input type="text" id="city" name="city" maxlength="35" required />
                                        </div>
                                        <div class="column column-50">
                                            <label for="town" class="form-title">{{ __("Town") }}</label>
                                            <input type="text" id="town" name="town" maxlength="35" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h1 class="section-title">{{ __("Form") }}</h1>
                            <div class="row">
                                <div class="column column-50">
                                    <label for="initial-date" class="form-title">{{ __("Initial date") }}</label>
                                    <input
                                        id="initial-date"
                                        name="initial_date"
                                        type="date"
                                        min="<?= date('Y-m-d'); ?>"
                                        required
                                    />
                                </div>
                                <div class="column column-50">
                                    <label for="final-date" class="form-title">{{ __("Final date") }}</label>
                                    <input
                                        type="text"
                                        id="final-date"
                                        placeholder="Preenche a data de início primeiro"
                                        required
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="column column-50">
                                    <span class="form-title">{{ __("Animals") }}</span>
                                    <div class="checkbox-container row">
                                        <div class="checkbox option">
                                            <input type="checkbox" id="dog" name="animals[]" value="Cão">
                                            <label for="dog">{{ ucfirst(__("dog")) }}</label>
                                        </div>
                                        <div class="checkbox option">
                                            <input type="checkbox" id="cat" name="animals[]" value="Gato">
                                            <label for="cat">{{ ucfirst(__("cat")) }}</label>
                                        </div>
                                        <div class="checkbox option">
                                            <input
                                                type="checkbox"
                                                id="others"
                                                name="animals[]"
                                                value="Outros"
                                            >
                                            <label for="others">{{ ucfirst(__("others")) }}</label>
                                        </div>
                                        <div class="otherAnimals">
                                            <input
                                                type="text"
                                                name="other_animals"
                                                id="otherAnimals"
                                                placeholder="{{ __("Which ones?") }}"
                                                required
                                                disabled
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="number-of-animals" class="form-title">{{ __("Number of Animals") }}</label>
                                    <div class="flex-column">
                                        <input
                                            type="number"
                                            id="number-of-animals"
                                            name="number_of_animals"
                                            max="2"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="temper" class="form-title">{{ __("Animal temper") }}</label>
                                    <span class="note">{{ __("For example: friendly, suspicious, etc.") }}</span>
                                    <textarea
                                        id="temper"
                                        name="animal_temper"
                                        rows="5"
                                        cols="86"
                                        required
                                    ></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <span class="form-title">{{ __("Daily visits") }}</span>
                                    <div class="radio-chips">
                                        <label>
                                            <input type="radio" name="visit_number" value=1 />
                                            <span>1</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="visit_number" value=2 />
                                            <span>2</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="visit_number" value=3 />
                                            <span>3</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label class="form-title">{{ __("Dog walks") }}</label>
                                    <span class="note">{{ __("If applicable") }}</span>
                                    <div class="flex-row">
                                        <div class="radio-container">
                                            <input type="radio" id="walk-yes" name="has_walk" value=yes>
                                            <label for="walk-yes">{{ ucfirst(__("yes")) }}</label>
                                        </div>
                                        <div class="radio-container">
                                            <input type="radio" id="walk-no" name="has_walk" value=no checked>
                                            <label for="walk-no">{{ ucfirst(__("no")) }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide">
                                <div class="column">
                                    <span class="form-title">{{ __("Daily walks") }}</span>
                                    <div class="radio-chips">
                                        <label>
                                            <input type="radio" name="walk_number" value=1 />
                                            <span>1</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="walk_number" value=2 />
                                            <span>2</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="walk_number" value=3 />
                                            <span>3</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="other-services">
                                <span class="form-title">{{ __("Other services") }} ({{ __("Optional") }})</span>
                                <div class="row checkbox-container-grid">
                                    <div class="column checkbox option">
                                        <input type="checkbox" id="food" name="services[]" value="Alimentação">
                                        <label for="food">{{ __("Food") }}</label>
                                    </div>
                                    <div class="column checkbox option">
                                        <input type="checkbox" id="medication" name="services[]" value="Dar medicação">
                                        <label for="medication">{{ __("Medication") }}</label>
                                    </div>
                                    <div class="column checkbox option">
                                        <input type="checkbox" id="hygiene" name="services[]" value="Cuidados de higiene">
                                        <label for="hygiene">{{ __("Hygiene care") }}</label>
                                    </div>
                                    <div class="column checkbox option">
                                        <input type="checkbox" id="play" name="services[]" value="Brincar">
                                        <label for="play">{{ __("Play") }}</label>
                                    </div>
                                    <div class="column checkbox option">
                                        <input type="checkbox" id="plants" name="services[]" value="Regar as plantas">
                                        <label for="plants">{{ __("Watering plants") }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="notes" class="form-title">{{ __("Notes") }} ({{ __("Optional") }})</label>
                                    <span class="note">{{ __("For example: VAT number, food, medication to give, how to take care of hygiene, etc.") }}</span>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="5"
                                        cols="86"
                                    ></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label class="form-title">{{ __("Do we have permission to publish photos of your pet during the petsitting service on Animais de Rua's communication channels to promote the service?") }}</label>
                                    <div class="flex-row">
                                        <div class="radio-container">
                                            <input type="radio" id="consent-yes" name="has_consent" value=yes>
                                            <label for="consent-yes">{{ ucfirst(__("yes")) }}</label>
                                        </div>
                                        <div class="radio-container">
                                            <input type="radio" id="consent-no" name="has_consent" value=no checked>
                                            <label for="consent-no">{{ ucfirst(__("no")) }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="prices-container" style="margin-top: 1rem">
                                <span class="prices-title">{{ __("Prices") }}</span>
                                <div class="prices">
                                    <div class="row">
                                        <div class="column column-50">
                                            <span class="visit-price">{{ __("Dog visit") }} {{ __("(up to 2 dogs)") }}</span>
                                            <ul>
                                                <li>{{ __("15,50€/day with 1 walk. 5€ per extra animal") }}</li>
                                                <li>{{ __("25,50€/day with 2 walks. 5€ per extra animal") }}</li>
                                                <li>{{ __("35,50€/day with 3 walks. 5€ per extra animal") }}</li>
                                            </ul>
                                        </div>
                                        <div class="column column-50">
                                            <span class="visit-price">{{ __("Cat visit") }} {{ __("(up to 2 cats)") }}</span>
                                            <ul>
                                                <li>{{ __("12,50€/day. 3€ extra per extra animal") }}</li>
                                                <li>{{ __("20€/day with 2 visits. 3€ per extra animal") }}</li>
                                                <li>{{ __("30€/day with 3 visits. 3€ per extra animal") }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 1rem; font-size: 1.4rem">
                                <p>{{ __("By purchasing this service you are helping Animais de Rua to reach more animals. If you need a receipt for this donation, please enter your VAT number in the field") }} <strong>{{ __("Notes") }}.</strong></p>
                                <p style="margin-bottom: 0">{{ __("The service includes sending daily photos and videos of your pet!") }}</p>
                            </div>
                            <div class="petsitting-error"></div>
                            <div class="submit-container">
                                <button type="submit" class="submit">
                                    {{ __("Send request") }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Disable final date picker if initial date is not selected
    const initialDatePicker = document.getElementById('initial-date');
    const finalDatePicker = document.getElementById('final-date');

    // Disable "other animals" input if checkbox is not selected
    const othersCheckbox = document.getElementById('others');
    const otherAnimalsInput = document.getElementById('otherAnimals');

    initialDatePicker.addEventListener('change', handleDateChange);
    othersCheckbox.addEventListener('change', handleOtherAnimalsChange);

    function handleDateChange(event) {
        if (initialDatePicker.value) {
            initialDatePicker.style.color = 'white';

            finalDatePicker.type = 'date';
            finalDatePicker.name = 'final_date';
            finalDatePicker.style.color = 'white';
            finalDatePicker.min = initialDatePicker.value;
            finalDatePicker.disabled = false;
        } else {
            initialDatePicker.style.color = '#a0a0a0';

            finalDatePicker.type = 'text';
            finalDatePicker.value = ''; 
            finalDatePicker.disabled = true;
        }
    }

    function handleOtherAnimalsChange(event) {
        if (event.target.checked) {
            otherAnimalsInput.disabled = false;
        } else {
            otherAnimalsInput.disabled = true;
            otherAnimalsInput.value = '';
        }
    }
</script>
