@component('admin.reports.report', [
        'title' => __('store'),
        'action' => 'store',
        'headquarters' => $headquarters
    ])

    @slot('filters')
    <select class="form-control form-control-sm" name="status">
        <option value="">{{ __("Any status") }}</option>
        <option value="waiting" selected>{{ ucfirst(__('waiting')) }}</option>
        <option value="in_progress">{{ ucfirst(__('in_progress')) }}</option>
        <option value="shipped">{{ ucfirst(__('shipped')) }}</option>
    </select>
    <br/>
    <span>
        <input class="form-control form-control-sm" type="date" name="start" />
        <input class="form-control form-control-sm" type="date" name="end" />
    </span>
    @endslot


    @slot('order')
    <select class="form-control form-control-sm" name="order[column]">
        <option value="reference">{{ __("Reference") }}</option>
        <option value="created_at" selected>{{ __("Date") }}</option>
        <option value="receipt">{{ __("Receipt") }}</option>
        <option value="expense">{{ __("Expense") }}</option>
        <option value="invoice">{{ __("Invoice") }}</option>
        <option value="price">{{ __("Price") }}</option>
        <option value="price_no_vat">{{ __("Price") }} ({{ __('no VAT') }})</option>
        <option value="discount">{{ __("Discounts") }}</option>
        <option value="discount_no_vat">{{ __("Discounts") }} ({{ __('no VAT') }})</option>
        <option value="expense">{{ __("Expense") }}</option>
        <option value="total_no_vat">{{ __("Total products") }} ({{ __('no VAT') }})</option>
        <option value="quantity">{{ __("Quantity") }}</option>
    </select>
    <select class="form-control form-control-sm" name="order[direction]">
        <option value="ASC">{{ __("Ascendent") }}</option>
        <option value="DESC" selected>{{ __("Descendent") }}</option>
    </select>
    @endslot

@endcomponent
