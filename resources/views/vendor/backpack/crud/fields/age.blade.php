<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <div style="max-width: 200px">
        <div class="input-group" style="margin-bottom: 4px">
            <input
                type="number"
                max="20"
                min="0"
                name="{{ $field['name'] }}[0]"
                value="{{ old($field['name']) ? old($field['name'][0]) : 
                    (isset($field['value']) ? $field['value'][0] :
                    (isset($field['default']) ? $field['default'][0] :'' )) }}"
                @include('crud::inc.field_attributes')
            >
            <div class="input-group-addon">{{ __("years") }}</div>
        </div>

        <div class="input-group">
            <input
                type="number"
                max="11"
                min="0"
                name="{{ $field['name'] }}[1]"
                value="{{ old($field['name']) ? old($field['name'][1]) : 
                    (isset($field['value']) ? $field['value'][1] :
                    (isset($field['default']) ? $field['default'][1] :'' )) }}"
                @include('crud::inc.field_attributes')
            >
            <div class="input-group-addon">{{ __("months") }}</div>
        </div>
    </div>

    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>