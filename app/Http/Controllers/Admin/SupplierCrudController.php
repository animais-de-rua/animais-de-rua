<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Requests\SupplierRequest as StoreRequest;
use App\Http\Requests\SupplierRequest as UpdateRequest;
use App\Models\Supplier;
use Backpack\CRUD\CrudPanel;

/**
 * Class SupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SupplierCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Supplier');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/store/supplier');
        $this->crud->setEntityNameStrings(__('supplier'), __('suppliers'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ----------
        // Columns
        $this->crud->setColumns(['reference', 'store_order_id', 'store_product_id', 'notes', 'status']);

        $this->crud->addColumn([
            'name' => 'reference',
            'label' => __('Reference'),
        ]);

        $this->crud->addColumn([
            'name' => 'store_order_id',
            'label' => ucfirst(__('order')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getOrderLinkAttribute',
        ]);

        $this->crud->addColumn([
            'name' => 'store_product_id',
            'label' => ucfirst(__('product')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProductLinkAttribute',
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status'),
        ]);

        // ----------
        // Fields
        $this->crud->addFields(['reference', 'store_order_id', 'store_product_id', 'notes', 'status']);

        $this->crud->addField([
            'label' => __('Reference'),
            'name' => 'reference',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('order')),
            'name' => 'store_order_id',
            'type' => 'select2_from_ajax',
            'entity' => 'order',
            'attribute' => 'name',
            'model' => '\App\Models\StoreOrder',
            'placeholder' => '',
            'minimum_input_length' => 2,
            'data_source' => url('admin/storeOrder/ajax/search/'),
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('product')),
            'name' => 'store_product_id',
            'type' => 'select2_from_ajax',
            'entity' => 'product',
            'attribute' => 'name',
            'model' => '\App\Models\StoreProduct',
            'placeholder' => '',
            'minimum_input_length' => 2,
            'data_source' => url('admin/storeProduct/ajax/search/'),
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'type' => 'textarea',
            'name' => 'notes',
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
        ]);

        // ----------
        // Filters
        $this->crud->addFilter([
            'name' => 'store_order_id',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('order')),
            'placeholder' => __('Select a order'),
        ],
            url('admin/storeOrder/ajax/filter/'),
            function ($value) {
                $this->crud->addClause('where', 'store_order_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'store_product_id',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('product')),
            'placeholder' => __('Select a product'),
        ],
            url('admin/storeProduct/ajax/filter/'),
            function ($value) {
                $this->crud->addClause('where', 'store_product_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            EnumHelper::translate('store.supplier'),
            function ($values) {
                $this->crud->addClause('whereIn', 'status', json_decode($values));
            });

        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in SupplierRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function showDetailsRow($id)
    {
        $supplier = Supplier::select(['id', 'notes'])->find($id);
        $notes = str_replace('\\n', '<br />', $supplier->notes);

        return "<div style='margin:5px 8px'>
                <b>" . __('Notes') . ":</b>
                <p style='white-space: pre-wrap;'><i>" . __('Notes') . "</i>: $notes</p>
            </div>";
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
