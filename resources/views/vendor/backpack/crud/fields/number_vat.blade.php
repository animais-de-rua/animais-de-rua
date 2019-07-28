{{-- number input --}}
<div @include('crud::inc.field_wrapper_attributes') >
    <label for="{{ $field['name'] }}">{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
        	type="number"
        	name="{{ $field['name'] }}"
            id="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::inc.field_attributes')
        	>
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif

    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@if ($crud->checkIfFieldIsFirstOfItsType($field))
    @push('crud_fields_scripts')
    <script>
        let vat = 1 + .01 * {{ \Config::get('settings.vat') }};
        let finalInput = document.querySelector('[name="{{ $field['name'] }}"]');
        let baseInput = document.querySelector('[name="{{ $field['base'] }}"]');

        baseInput.oninput = e => finalInput.value = (baseInput.value / vat).toFixed(2);
    </script>
    @endpush
@endif
