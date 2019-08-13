<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreStockRequest as StoreRequest;
use App\Http\Requests\StoreStockRequest as UpdateRequest;
use App\User;
use DB;

/**
 * Class StoreStockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StoreStockCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StoreStock');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/store/user/stock');
        $this->crud->setEntityNameStrings(__('stock'), __('stock'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ----------
        // Columns
        $this->crud->addColumns(['id', 'user_id', 'store_product_id', 'quantity', 'description']);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user_id',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->setColumnDetails('store_product_id', [
            'name' => 'store_product_id',
            'label' => ucfirst(__('product')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProductLinkAttribute',
        ]);

        $this->crud->setColumnDetails('quantity', [
            'name' => 'quantity',
            'label' => __('Quantity'),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getQuantityColorize',
        ]);

        $this->crud->setColumnDetails('description', [
            'type' => 'text',
            'label' => __('Description'),
        ]);

        // ----------
        // Fields
        $this->crud->addFields(['description', 'user_id', 'store_product_id', 'quantity', 'notes']);

        $this->crud->addField([
            'label' => __('Description'),
            'type' => 'textarea',
            'name' => 'description',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('volunteer')),
            'name' => 'user_id',
            'type' => 'select2_from_ajax',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => '\App\User',
            'placeholder' => '',
            'minimum_input_length' => 2,
            'data_source' => url('admin/user/ajax/search/' . User::ROLE_STORE),
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
            'label' => __('Value'),
            'name' => 'quantity',
            'type' => 'number',
            'default' => 0,
            'suffix' => '€',
            'attributes' => [
                'min' => -1000000,
                'max' => 1000000,
                'step' => .01,
            ],
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'type' => 'textarea',
            'name' => 'notes',
        ]);

        // ----------
        // Filters
        if (is(['admin'], ['store stock'])) {
            $this->crud->addFilter([
                'name' => 'user_id',
                'type' => 'select2_ajax',
                'label' => ucfirst(__('volunteer')),
                'placeholder' => __('Select a volunteer'),
            ],
                url('admin/user/ajax/filter/' . User::ROLE_VOLUNTEER),
                function ($value) {
                    $this->crud->addClause('where', 'user_id', $value);
                });
        }

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
            'name' => 'number',
            'type' => 'range',
            'label' => sprintf('%s (%s)', __('Quantity'), 'unidades'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'quantity', '>=', (float) $range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'quantity', '<=', (float) $range->to);
                }
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'group_products',
            'label' => __('Merge with sent data'),
        ],
            false,
            function ($values) {
                if ($values) {
                    $sent_products_query = 'SELECT `store_orders`.user_id as s_user_id, `store_orders_products`.store_product_id as s_store_product_id, SUM(quantity) as s_quantity
                        FROM `store_orders`, `store_orders_products`
                        WHERE `store_orders`.id = `store_orders_products`.store_order_id
                        GROUP BY `store_orders_products`.store_product_id, `store_orders`.user_id';

                    $this->crud->query->leftjoin(DB::raw("($sent_products_query) sent"), function ($join) {
                        $join
                            ->on('sent.s_user_id', '=', 'store_stock.user_id')
                            ->on('sent.s_store_product_id', '=', 'store_stock.store_product_id');
                    });

                    $this->crud->groupBy(['store_product_id', 'user_id']);

                    $this->crud->query->selectRaw('(SUM(quantity) - IFNULL(s_quantity, 0)) as quantity');

                    $this->crud->denyAccess(['update', 'delete']);
                }
            });

        $this->crud->addClause('with', ['user', 'product']);

        $this->crud->addClause('orderBy', 'store_stock.id', 'DESC');

        // Permissions
        if (!is(['admin'], ['store stock'])) {
            $this->crud->denyAccess(['create', 'update', 'delete']);

            $this->crud->addClause('where', 'user_id', backpack_user()->id);
        }

        // add asterisk for fields that are required in StoreTransactionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
