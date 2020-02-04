@component('admin.reports.report', [
        'title' => __('store'),
        'action' => 'store',
        'order' => $order,
    ])

    @slot('filters')
    {{-- Products --}}
    <select multiple="multiple" class="form-control form-control-sm" name="products[]">
        <option value="">{{ __("Every product") }}</option>
        @foreach($store_products as $product)
        <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
    </select>
    <br />

    {{-- Volunteers --}}
    <select class="form-control form-control-sm" name="volunteer">
        <option value="">{{ __("Every volunteer") }}</option>
        @foreach($volunteers as $volunteer)
        <option value="{{ $volunteer->id }}">{{ $volunteer->name }} ({{ $volunteer->id }})</option>
        @endforeach
    </select>
    <br/>

    {{-- Status --}}
    <select class="form-control form-control-sm" name="status">
        <option value="" selected>{{ __("Any status") }}</option>
        @foreach($status as $value)
        <option value="{{ $value }}">{{ ucfirst(__($value)) }}</option>
        @endforeach
    </select>
    <br/>

    {{-- Date --}}
    <span>
        <input class="form-control form-control-sm" type="date" name="start" />
        <input class="form-control form-control-sm" type="date" name="end" />
    </span>
    @endslot

@endcomponent
