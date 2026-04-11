{{-- number input --}}
<div @include('crud::inc.field_wrapper_attributes') >
    <label for="{{ $field['name'] }}">{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif
        <input
        	type="number"
        	name="{{ $field['name'] }}"
            id="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::inc.field_attributes')
        	>
        @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif

    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] !!}</small>
    @endif
</div>

@if ($crud->checkIfFieldIsFirstOfItsType($field))
    @push('crud_fields_scripts')
    <script>
        let vatInput = document.querySelector('select[name="vat"]');
        let finalInput = document.querySelector('[name="{{ $field['name'] }}"]');
        let baseInput = document.querySelector('[name="{{ $field['base'] }}"]');

        vatInput.oninput = baseInput.oninput = e => finalInput.value = (baseInput.value / (1 + .01 * parseInt(vatInput.value))).toFixed(2);
    </script>
    @endpush
@endif
