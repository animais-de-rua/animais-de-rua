@component('admin.reports.report', [
        'title' => __('Abandoned Animals'),
        'action' => 'abandoned_animals',
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

    <span>
        <input class="form-control form-control-sm" type="date" name="start" />
        <input class="form-control form-control-sm" type="date" name="end" />
    </span>
    @endslot

@endcomponent
