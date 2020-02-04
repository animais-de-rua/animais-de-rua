@component('admin.reports.report', [
        'title' => __('donations'),
        'action' => 'donation',
        'order' => $order,
        'group' => $group,
    ])

    @slot('filters')
    <select class="form-control form-control-sm" name="type" toggler="type">
        <option value="">{{ __("Every type") }}</option>
        @foreach($types as $type)
        <option value="{{ $type }}">{{ ucfirst(__($type)) }}</option>
        @endforeach
    </select>
    <select class="form-control form-control-sm" name="headquarter" disabled="disabled" parent-toggler="type">
        <option value="">{{ __("Every headquarter") }}</option>
        @foreach($headquarters as $headquarter)
        <option value="{{ $headquarter->id }}">{{ $headquarter->name }}</option>
        @endforeach
    </select>
    <select class="form-control form-control-sm" name="protocol" disabled="disabled" parent-toggler="type">
        <option value="">{{ __("Every protocol") }}</option>
        @foreach($protocols as $protocol)
        <option value="{{ $protocol->id }}">{{ $protocol->name }}</option>
        @endforeach
    </select>
    <br/>
    <span>
        <input class="form-control form-control-sm" type="date" name="start" />
        <input class="form-control form-control-sm" type="date" name="end" />
    </span>
    @endslot

@endcomponent
