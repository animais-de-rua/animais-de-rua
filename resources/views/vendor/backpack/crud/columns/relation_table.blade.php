<?php
$item_name = strtolower(isset($column['entity_singular']) && !empty($column['entity_singular']) ? $column['entity_singular'] : $column['label']);

$items = old($column['name']) ? (old($column['name'])) : (isset($column['value']) ? ($column['value']) : (isset($column['default']) ? ($column['default']) : ''));

if ($items == '') {
    return;
}
$crudRouteOriginal = $crud->route;
$crud->route = $column['route'];
?>

<div @include('crud::inc.field_wrapper_attributes') >

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0">

            <thead>
                <tr>
                    @foreach( $column['columns'] as $col )
                    <th>
                        {{ __($col['label']) }}
                    </th>
                    @endforeach
                    <th data-orderable="false">{{ trans('backpack::crud.actions') }}</th>
                </tr>
            </thead>
            <tbody class="table-striped">
                @foreach( $items as $item)
                <tr class="array-row">
                    @foreach( $column['columns'] as $col)
                    @php
                    $value = $raw_value = isset($col['attribute']) ? $item->{$col['name']}->{$col['attribute']} : $item->{$col['name']};
                    if(isset($col['translate']))
                        $value = __($value);
                    @endphp
                    <td class="{{ isset($col['classify']) ? $raw_value : '' }}">
                        {!! $value !!}
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

        @if(in_array('add', $column['buttons']))
        <div class="array-controls btn-group m-t-10">
            <a href="{{ $url }}">
                <button class="btn btn-sm btn-default" type="button"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}}</button>
            </a>
        </div>
        @endif
    </div>
</div>

<?php $crud->route = $crudRouteOriginal;?>