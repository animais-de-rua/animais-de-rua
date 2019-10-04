@component('admin.reports.report', [
        'title' => __('store'),
        'action' => 'store',
        'order' => $order,
    ])

    @slot('filters')
    <select multiple="multiple" class="form-control form-control-sm" name="products[]">
        <option value="">{{ __("Every product") }}</option>
        @foreach($store_products as $product)
        <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
    </select>
    <br />
    <select class="form-control form-control-sm" name="status">
        <option value="" selected>{{ __("Any status") }}</option>
        <option value="waiting">{{ ucfirst(__('waiting')) }}</option>
        <option value="in_progress">{{ ucfirst(__('in_progress')) }}</option>
        <option value="shipped">{{ ucfirst(__('shipped')) }}</option>
    </select>
    <br/>
    <span>
        <input class="form-control form-control-sm" type="date" name="start" />
        <input class="form-control form-control-sm" type="date" name="end" />
    </span>
    @endslot

@endcomponent
