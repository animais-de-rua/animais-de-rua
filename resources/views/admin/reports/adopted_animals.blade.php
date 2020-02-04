@component('admin.reports.report', [
        'title' => ucfirst(__('adoptions')),
        'action' => 'adopted_animals',
    ])

    @slot('filters')
    {{-- Headquarters --}}
    <select class="form-control form-control-sm" name="headquarter">
        <option value="">{{ __("Every headquarter") }}</option>
        @foreach($headquarters as $headquarter)
        <option value="{{ $headquarter->id }}">{{ $headquarter->name }}</option>
        @endforeach
    </select>

    {{-- Territories --}}
    <br/>
    @foreach($territories as $name => $list)
    <select class="form-control form-control-sm" name="{{ $name }}">
        <option value="">{{ __("Every $name") }}</option>
        @foreach($list as $territory)
        <option value="{{ $territory->id }}" parent="{{ $territory->parent_id ?: '' }}">{{ $territory->name }}</option>
        @endforeach
    </select>
    @endforeach

    {{-- Protocols --}}
    <br/>
    <select class="form-control form-control-sm" name="protocol">
        <option value="">{{ __("Every protocol") }}</option>
        @foreach($protocols as $protocol)
        <option value="{{ $protocol->territory_id }}">{{ $protocol->name }}</option>
        @endforeach
    </select>

    {{-- Vets --}}
    <br/>
    <select class="form-control form-control-sm" name="vet">
        <option value="">{{ __("Every vet") }}</option>
        @foreach($vets as $vet)
        <option value="{{ $vet->id }}">{{ $vet->id }} - {{ $vet->name }}</option>
        @endforeach
    </select>

    {{-- Status --}}
    <br/>
    <select class="form-control form-control-sm" name="status">
        <option value="" selected>{{ __("Any status") }}</option>
        @foreach($status as $value)
        <option value="{{ $value }}">{{ ucfirst(__($value)) }}</option>
        @endforeach
    </select>

    {{-- Extras --}}
    <br/>
    <div style="line-height: 24px; margin: 10px 0px;">
        <input type="checkbox" id="sterilized" name="sterilized"> <label for="sterilized">{{ ucfirst(__("sterilized")) }}</label><br/>
        <input type="checkbox" id="vaccinated" name="vaccinated"> <label for="vaccinated">{{ ucfirst(__("vaccinated")) }}</label><br/>
        <input type="checkbox" id="processed" name="processed"> <label for="processed">{{ ucfirst(__("processed")) }}</label><br/>
        <input type="checkbox" id="individual" name="individual"> <label for="individual">{{ __("Individual Animal") }}</label><br/>
        <input type="checkbox" id="docile" name="docile"> <label for="docile">{{ __("Docile cat of colony") }}</label><br/>
        <input type="checkbox" id="foal" name="foal"> <label for="foal">{{ __("Foal") }}</label><br/>
        <input type="checkbox" id="abandoned" name="abandoned"> <label for="abandoned">{{ __("Abandoned cat of colony") }}</label><br/>
    </div>

    <span>
        <input class="form-control form-control-sm" type="date" name="start" />
        <input class="form-control form-control-sm" type="date" name="end" />
    </span>
    @endslot

@endcomponent
