<!-- array input -->

<?php
$max = -1;
$min = 1;
$item_name = __('product');

$items = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? [];

// make sure not matter the attribute casting
// the $items variable contains a properly defined JSON
if (is_object($items)) {
    $items = $items->toArray();
}
if (is_array($items)) {
    if (count($items)) {
        $items = json_encode($items);
    } else {
        $items = '[]';
    }
} elseif (is_string($items) && !is_array(json_decode($items))) {
    $items = '[]';
}

?>
<div ng-app="backPackTableApp" ng-controller="tableController" @include('crud::inc.field_wrapper_attributes') >

    <label>{{ __('Cart') }}</label>
    @include('crud::inc.field_translatable_icon')

    <input class="array-json" type="hidden" id="{{ $field['name'] }}" name="{{ $field['name'] }}">

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0" ng-init="field = '#{{ $field['name'] }}'; items = {{ $items }}; max = {{$max}}; min = {{$min}}; maxErrorTitle = '{{trans('backpack::crud.table_cant_add', ['entity' => $item_name])}}'; maxErrorMessage = '{{trans('backpack::crud.table_max_reached', ['max' => $max])}}'">

            <thead>
                <tr>
                    <th style="font-weight: 600!important;">
                        {{ ucfirst(__('product')) }}
                    </th>
                    <th style="font-weight: 600!important;">
                        {{ __('Quantity') }}
                    </th>

                    @if(!$field['readonly'])
                    <th style="width: 50px;" class="text-center" ng-if="max == -1 || max > 1"> {{-- <i class="fa fa-sort"></i> --}} </th>
                    <th style="width: 50px;" class="text-center" ng-if="max == -1 || max > 1"> {{-- <i class="fa fa-trash"></i> --}} </th>
                    @endif
                </tr>
            </thead>

            <tbody ui-sortable="sortableOptions" ng-model="items" class="table-striped">

                <tr ng-repeat="item in items" class="array-row">

                    {{-- Select 2 field --}}
                    <td>
                        <select ng-model="item.pivot.store_product_id"
                            style="width: 100%"
                            @include('crud::inc.field_attributes', ['default_class' => 'form-control select2_from_array'])>
                            @foreach ($field['options'] as $key => $value)
                            <option
                                ng-selected="item.id == {{ $key }}"
                                ng-value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <input
                            @include('crud::inc.field_attributes', ['default_class' => 'form-control input-sm'])
                            type="number"
                            step="1"
                            ng-model="item.pivot.quantity"
                            style="height: 34px;">
                    </td>

                    @if(!$field['readonly'])
                    <td ng-if="max == -1 || max > 1">
                        <span class="btn btn-sm btn-default sort-handle"><span class="sr-only">sort item</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i></span>
                    </td>
                    <td ng-if="max == -1 || max > 1">
                        <button ng-hide="min > -1 && $index < min" class="btn btn-sm btn-default" type="button" ng-click="removeItem(item);"><span class="sr-only">delete item</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>
                    </td>
                    @endif
                </tr>

            </tbody>

        </table>

        @if(!$field['readonly'])
        <div class="array-controls btn-group m-t-10">
            <button ng-if="max == -1 || items.length < max" class="btn btn-sm btn-default" type="button" ng-click="addItem()"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}} {{ $item_name }}</button>
        </div>
        @endif

    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-sortable/0.14.3/sortable.min.js"></script>

        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            function initSelect2() {
                setTimeout(function(){
                    $('.select2_from_array').each(function (i, obj) {
                        if (!$(obj).hasClass("select2-hidden-accessible")) {
                            $(obj).select2({
                                theme: "bootstrap"
                            });
                        }
                    });
                }, 0);
            }

            jQuery(document).ready(initSelect2);
        </script>

        <script>
            window.angularApp = window.angularApp || angular.module('backPackTableApp', ['ui.sortable'], function($interpolateProvider){
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

            window.angularApp.controller('tableController', function($scope){

                $scope.sortableOptions = {
                    handle: '.sort-handle',
                    axis: 'y',
                    helper: function(e, ui) {
                        ui.children().each(function() {
                            $(this).width($(this).width());
                        });
                        return ui;
                    },
                };

                $scope.addItem = function(){

                    if( $scope.max > -1 ){
                        if( $scope.items.length < $scope.max ){
                            var item = {};
                            $scope.items.push(item);
                        } else {
                            new PNotify({
                                title: $scope.maxErrorTitle,
                                text: $scope.maxErrorMessage,
                                type: 'error'
                            });
                        }
                    }
                    else {
                        var item = { pivot: { quantity: 1 } };
                        $scope.items.push(item);
                    }

                    initSelect2();
                }

                $scope.removeItem = function(item){
                    var index = $scope.items.indexOf(item);
                    $scope.items.splice(index, 1);
                }

                $scope.$watch('items', function(a, b){

                    if( $scope.min > -1 ){
                        while($scope.items.length < $scope.min){
                            $scope.addItem();
                        }
                    }

                    if( typeof $scope.items != 'undefined' ){

                        if( typeof $scope.field != 'undefined'){
                            if( typeof $scope.field == 'string' ){
                                $scope.field = $($scope.field);
                            }
                            $scope.field.val( $scope.items.length ? angular.toJson($scope.items) : null );
                        }
                    }
                }, true);

                if( $scope.min > -1 ){
                    for(var i = 0; i < $scope.min; i++){
                        $scope.addItem();
                    }
                }
            });

            angular.element(document).ready(function(){
                angular.forEach(angular.element('[ng-app]'), function(ctrl){
                    var ctrlDom = angular.element(ctrl);
                    if( !ctrlDom.hasClass('ng-scope') ){
                        angular.bootstrap(ctrl, [ctrlDom.attr('ng-app')]);
                    }
                });
            })

        </script>

    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
