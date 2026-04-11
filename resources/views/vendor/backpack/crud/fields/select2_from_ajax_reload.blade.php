<!-- select2 from ajax -->
@php
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <?php $entity_model = $crud->model; ?>

    <select
        name="{{ $field['name'] }}"
        style="width: 100%"
        id="select2_ajax_{{ $field['name'] }}"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
        >

        @if ($old_value)
            @php
                $item = $connected_entity->find($old_value);
            @endphp
            @if ($item)

            {{-- allow clear --}}
            @if ($entity_model::isColumnNullable($field['name']))
            <option value="" selected>
                {{ $field['placeholder'] }}
            </option>
            @endif

            <option value="{{ $item->getKey() }}" selected>
                {{ $item->{$field['attribute']} }}
            </option>
            @endif
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] !!}</small>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    {{-- allow clear --}}
    @if ($entity_model::isColumnNullable($field['name']))
    <style type="text/css">
        .select2-selection__clear::after {
            content: ' {{ trans('backpack::crud.clear') }}';
        }
    </style>
    @endif
    @endpush

@endif

<!-- include field specific select2 js-->
@push('crud_fields_scripts')
<script>
    jQuery(document).ready(function($) {
        // trigger select2 for each untriggered select2 box
        $("#select2_ajax_{{ $field['name'] }}").each(function (i, obj) {
            var form = $(obj).closest('form');

            if (!$(obj).hasClass("select2-hidden-accessible"))
            {
                $(obj).select2({
                    multiple: false,
                    placeholder: "{{ $field['placeholder'] }}",
                    minimumInputLength: "{{ $field['minimum_input_length'] }}",

                    {{-- allow clear --}}
                    @if ($entity_model::isColumnNullable($field['name']))
                    allowClear: true,
                    @endif
                    ajax: {
                        url: "{{ $field['data_source'] }}",
                        type: '{{ $field['method'] ?? 'GET' }}',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "{{ $field['attribute'] }}";
                                    return {
                                        text: item[textField],
                                        id: item["{{ $connected_entity_key_name }}"]
                                    }
                                }),
                               pagination: {
                                     more: data.current_page < data.last_page
                               }
                            };

                            return result;
                        },
                        cache: true
                    },
                })
                {{-- allow clear --}}
                @if ($entity_model::isColumnNullable($field['name']))
                .on('select2:unselecting', function(e) {
                    $(this).val('').trigger('change');
                    e.preventDefault();
                })
                @endif
                ;

            }
        });

        @if (isset($field['dependencies']))
            @foreach (array_wrap($field['dependencies']) as $dependency)
                $('input[name={{ $dependency }}], select[name={{ $dependency }}], checkbox[name={{ $dependency }}], radio[name={{ $dependency }}], textarea[name={{ $dependency }}]').change(function () {
                    $("#select2_ajax_{{ $field['name'] }}").val(null).trigger("change");
                });
            @endforeach
        @endif

        let select = document.querySelector('[name="{{ $field['name'] }}"]');
        select.onchange = e => document.location.href = document.location.origin + document.location.pathname + "?{{ $field['entity'] }}="  + e.target.value;
    });
</script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
