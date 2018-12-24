<div id="forms">
    <div class="container">
        <div class="nav">
            <i class="icon icon-logo"></i>
            <i class="icon icon-close" onclick="app.closeForm()"></i>
        </div>
        <div class="row scrollable">
            <div class="column column-80 column-offset-10">
                <div class="header hide">
                    <h1>{{ __("Contact Animais de Rua") }}</h1>
                    <h2>{{ __("web.forms.interest") }}</h2>

                    <div class="row row-wrap">
                        <div class="column column-50">
                            <select class="options" onchange="app.onFormCategorySelect(this)">
                                <option value="volunteer">{{ __("web.forms.options.volunteer") }}</option>
                                <option value="contact">{{ __("web.forms.options.contact") }}</option>
                                <option value="apply">{{ __("web.forms.options.apply") }}</option>
                                <option value="training">{{ __("web.forms.options.training") }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="godfather hide">
                    <h1></h1>
                </div>

                {{-- FORM Voluntary --}}
                <form action="">
                    <div class="form volunteer row row-wrap">
                        <div class="column column-50">
                            <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                            <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                            <input type="tel" required name="phone" placeholder="{{ __("Phone") }}" />
                            <select required name="district" autocomplete="address-level1" class="dark empty" onchange="app.onFormDistrictSelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("district")) }}</option>
                                @foreach($form_all_territories[0] as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>
                            <select required name="county" autocomplete="address-level2" class="dark empty" onchange="app.checkEmptySelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("county")) }}</option>
                                @foreach($form_all_territories[1] as $county)
                                <option value="{{ $county['id'] }}" parent="{{ $county['parent_id'] }}" class="hide">{{ $county['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="text" required name="schedule" class="last-child" placeholder="{{ __("Availability Schedule") }}" />
                        </div>
                        <div class="column column-50">
                            <textarea required name="observations" style="height: 100%" placeholder="{{ __("Observations") }}"></textarea>
                        </div>
                        <div class="submit column column-50 column-offset-50">
                            <input type="submit" value="{{ __("Send") }}" />
                        </div>
                    </div>
                </form>

                {{-- FORM Contact --}}
                <form action="">
                    <div class="form contact row row-wrap hide">
                        <div class="column column-50">
                            <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                            <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                            <input type="tel" required name="phone" placeholder="{{ __("Phone") }}" />
                            <select required name="district" autocomplete="address-level1" class="dark empty" onchange="app.onFormDistrictSelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("district")) }}</option>
                                @foreach($form_all_territories[0] as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>
                            <select required name="county" autocomplete="address-level2" class="dark last-child empty" onchange="app.checkEmptySelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("county")) }}</option>
                                @foreach($form_all_territories[1] as $county)
                                <option value="{{ $county['id'] }}" parent="{{ $county['parent_id'] }}" class="hide">{{ $county['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="column column-50">
                            <textarea required name="observations" style="height: 100%" placeholder="{{ __("Observations") }}"></textarea>
                        </div>
                        <div class="submit column column-50 column-offset-50">
                            <input type="submit" value="{{ __("Send") }}" />
                        </div>
                    </div>
                </form>

                {{-- FORM Training --}}
                <form action="">
                    <div class="form training row row-wrap hide">
                        <div class="column column-50">
                            <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                            <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                            <input type="tel" required name="phone" placeholder="{{ __("Phone") }}" />
                            <select required name="district" autocomplete="address-level1" class="dark empty" onchange="app.onFormDistrictSelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("district")) }}</option>
                                @foreach($form_all_territories[0] as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>
                            <select required name="county" autocomplete="address-level2" class="dark empty" onchange="app.checkEmptySelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("county")) }}</option>
                                @foreach($form_all_territories[1] as $county)
                                <option value="{{ $county['id'] }}" parent="{{ $county['parent_id'] }}" class="hide">{{ $county['name'] }}</option>
                                @endforeach
                            </select>
                            <select required name="theme" class="dark last-child empty" onchange="app.checkEmptySelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("Themes of interest")) }}</option>
                                @foreach(\App\Helpers\EnumHelper::get('forms.themes') as $key => $value)
                                <option value="{{ $key }}">{{ ucfirst(__($value)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="column column-50">
                            <textarea required name="observations" style="height: 100%" placeholder="{{ __("Observations") }}"></textarea>
                        </div>
                        <div class="submit column column-50 column-offset-50">
                            <input type="submit" value="{{ __("Send") }}" />
                        </div>
                    </div>
                </form>

                {{-- FORM Sterilize --}}
                <form action="">
                    <div class="form apply row row-wrap hide">
                        <div class="column column-50">
                            <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                            <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                            <input type="tel" required name="phone" placeholder="{{ __("Phone") }}" />
                            <input type="text" required name="process" placeholder="{{ __("Process name") }}" />
                            <div class="row row-wrap">
                                <div class="column column-50">
                                    <input type="number" required name="animals" min="0" max="99" placeholder="{{ __("Number of Animals") }}" />
                                </div>
                                <div class="column column-50">
                                    <select class="dark empty" required name="specie" onchange="app.checkEmptySelect(this)">
                                        <option value="" disabled selected>{{ __("Specie") }}</option>
                                        @foreach(\App\Helpers\EnumHelper::get('process.specie') as $key => $value)
                                        <option value="{{ $key }}">{{ ucfirst(__($value)) }}</option>
                                        @endforeach
                                        <option value="other">{{ ucfirst(__('other')) }}</option>
                                    </select>
                                </div>
                            </div>
                            <select required name="district" autocomplete="address-level1" class="dark empty" onchange="app.onFormDistrictSelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("district")) }}</option>
                                @foreach($form_acting_territories[0] as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>
                            <select required name="county" autocomplete="address-level2" class="dark empty" onchange="app.onFormDistrictSelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("county")) }}</option>
                                @foreach($form_acting_territories[1] as $county)
                                <option value="{{ $county['id'] }}" parent="{{ $county['parent_id'] }}" class="hide">{{ $county['name'] }}</option>
                                @endforeach
                            </select>
                            <select required name="parish" autocomplete="address-level3" class="dark empty" onchange="app.checkEmptySelect(this)">
                                <option value="" disabled selected>{{ ucfirst(__("parish")) }}</option>
                                @foreach($form_acting_territories[2] as $parish)
                                <option value="{{ $parish['id'] }}" parent="{{ $parish['parent_id'] }}" class="hide">{{ $parish['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="file" class="last-child" required multiple name="images" placeholder="{{ __("Photos") }}" style="padding: 9px;"/>
                        </div>
                        <div class="column column-50 colab">
                            <h1>{{ __("web.forms.colab.title") }}</h1>
                            <div>
                                <input type="checkbox" id="colab01" name="colab01" value="colab01" />
                                <label for="colab01">{{ __("web.forms.colab.1") }}</label>
                            </div>
                            <div>
                                <input type="checkbox" id="colab02" name="colab02" value="colab02" />
                                <label for="colab02">{{ __("web.forms.colab.2") }}</label>
                            </div>
                            <div>
                                <input type="checkbox" id="colab03" name="colab03" value="colab03" />
                                <label for="colab03">{{ __("web.forms.colab.3") }}</label>
                            </div>
                            <div>
                                <input type="checkbox" id="colab04" name="colab04" value="colab04" />
                                <label for="colab04">{{ __("web.forms.colab.4") }}</label>
                            </div>
                            <textarea required name="observations" style="height: 160px; margin: 10px 0 6px;" placeholder="{{ __("Observations") }}"></textarea>
                            <div class="submit" style="margin: 0">
                                <input type="submit" value="{{ __("Send") }}" />
                            </div>
                        </div>
                    </div>
                </form>

                {{-- FORM Godfather --}}
                <form action="">
                    <div class="form godfather row row-wrap hide">
                        <div class="column column-50">
                            <input type="text" required name="name" placeholder="{{ __("Name") }}" />
                            <input type="email" required name="email" placeholder="{{ __("Email") }}" />
                            <input type="tel" required name="phone" placeholder="{{ __("Phone") }}" />
                            <div class="value">
                                <h2>{{ __("Godfather value") }}</h2>
                                <div class="input">
                                    <input type="checkbox" id="value01" name="value01" value="15€" />
                                    <label for="value01">15€</label>
                                    <input type="checkbox" id="value02" name="value02" value="30€" />
                                    <label for="value02">30€</label>
                                    <input type="text" name="other" placeholder="{{ ucfirst(__("other")) }}" class="last-child" />
                                </div>
                            </div>
                        </div>
                        <div class="column column-50 colab">
                            <textarea required name="observations" style="height: calc(100% - 56px); margin: 0 0 6px;" placeholder="{{ __("Observations") }}"></textarea>
                            <div class="submit" style="margin: 0">
                                <input type="submit" value="{{ __("Send") }}" />
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
