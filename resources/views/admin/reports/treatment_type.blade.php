@extends('admin.reports.report', [
    'title' => __('treatment types'),
    'action' => 'treatment_type',
])


@section('filters')
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
@endsection


@section('order')
<select class="form-control form-control-sm" name="order[column]">
    <option value="affected_animals">{{ __("Affected Animals") }}</option>
    <option value="affected_animals_new">{{ __("New affected Animals") }}</option>
    <option value="expense" selected>{{ __("Expense") }}</option>
    <option value="name">{{ __("Name") }}</option>
</select>
<select class="form-control form-control-sm" name="order[direction]">
    <option value="ASC">{{ __("Ascendent") }}</option>
    <option value="DESC" selected>{{ __("Descendent") }}</option>
</select>
@endsection
