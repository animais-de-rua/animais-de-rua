<?php
    if(!isset($entry))
        return;
?>
<div @include('crud::inc.field_wrapper_attributes') >

    <h3 style="margin-top:0">{!! $field['label'] !!}</h3>

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0">
            <tbody class="table-striped">
                @foreach( $field['rows'] as $item)
                <tr class="array-row">
                    <th style="width: 220px">{!! $item['label'] !!}</th>
                    <td>{!! $entry->{$item['value']}() !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>