<?php
    $item_name = strtolower(isset($field['entity_singular']) && !empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);

    $items = old($field['name']) ? (old($field['name'])) : (isset($field['value']) ? ($field['value']) : (isset($field['default']) ? ($field['default']) : '' ));

    if($items == "")
        return;

    $crud->route = $field['route'];
?>

<div @include('crud::inc.field_wrapper_attributes') >

    <h3 style="margin-top:0">{!! $field['label'] !!}</h3>

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0">

            <thead>
                <tr>
                    @foreach( $field['columns'] as $column )
                    <th>
                        {{ __($column['label']) }}
                    </th>
                    @endforeach
                    <th data-orderable="false">{{ trans('backpack::crud.actions') }}</th>
                </tr>
            </thead>
            <tbody class="table-striped">
                @foreach( $items as $item)
                <tr class="array-row">
                    @foreach( $field['columns'] as $column)
                    <td>
                        {!! isset($column['attribute']) ? $item->{$column['name']}->{$column['attribute']} : $item->{$column['name']} !!}
                    </td>
                    @endforeach
                    <td>
                        @include('crud::buttons.update', ['entry' => $item])
                        @include('crud::buttons.delete', ['entry' => $item])
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        @php
            // Add relation entity
            $reflection = new ReflectionClass(get_class($crud->getModel()));
            $classname = strtolower($reflection->getShortName());

            $url = "{$crud->route}/create?$classname={$crud->entry->id}";
        @endphp

        <div class="array-controls btn-group m-t-10">
            <a href="{{ $url }}">
                <button class="btn btn-sm btn-default" type="button"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}}</button>
            </a>
        </div>

    </div>
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
        <script>

        </script>

    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
