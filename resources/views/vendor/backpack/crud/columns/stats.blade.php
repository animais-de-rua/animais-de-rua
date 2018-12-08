<?php
if (!isset($entry)) {
    return;
}

?>
<div @include('crud::inc.field_wrapper_attributes') >

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0">
            <tbody class="table-striped">
                @foreach( $column['rows'] as $item)
                <tr class="array-row">
                    <th style="width: 220px">{!! $item['label'] !!}</th>
                    <td>{!! $entry->{$item['value']}() !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
