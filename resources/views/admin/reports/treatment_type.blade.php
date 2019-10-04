@component('admin.reports.report', [
        'title' => __('treatment types'),
        'action' => 'treatment_type',
        'order' => $order,
    ])

    @slot('filters')
    <select class="form-control form-control-sm" name="headquarter">
        <option value="">{{ __("Every headquarter") }}</option>
        @foreach($headquarters as $headquarter)
        <option value="{{ $headquarter->id }}">{{ $headquarter->name }}</option>
        @endforeach
    </select>
    <select class="form-control form-control-sm" name="status">
        <option value="">{{ __("Any status") }}</option>
        <option value="approved" selected>{{ __('approved') }}</option>
        <option value="approving">{{ __('approving') }}</option>
    </select>
    <br/>
    <span>
        <input class="form-control form-control-sm" type="date" name="start" />
        <input class="form-control form-control-sm" type="date" name="end" />
    </span>
    @endslot

@endcomponent
