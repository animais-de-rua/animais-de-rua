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
                                        <input type="text" required name="postalcode" placeholder="{{ __("Postal Code") }}" />
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
                                        <div class="column column-50"><input type="file" class="last-child" required name="images[0]" placeholder="{{ __("Photos") }}"/></div>
                                        <div class="column column-50"><input type="file" class="last-child" required name="images[1]" placeholder="{{ __("Photos") }}"/></div>
                                    </div>
                                    <div class="row row-wrap">
                                        <div class="column column-50"><input type="file" class="last-child" required name="images[2]" placeholder="{{ __("Photos") }}"/></div>
                                        <div class="column column-50"><input type="file" class="last-child" name="images[3]" placeholder="{{ __("Photos") }}"/></div>
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

                </div>
            </div>
        </div>
    </div>
</div>
