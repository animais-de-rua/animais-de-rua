<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreProductRequest as StoreRequest;
use App\Http\Requests\StoreProductRequest as UpdateRequest;

/**
 * Class StoreProductsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StoreProductCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StoreProduct');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/store/products');
        $this->crud->setEntityNameStrings(__('product'), __('products'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Price'),
            'name' => 'price',
            'type' => 'number',
            'attributes' => [
                'step' => 0.01,
            ],
        ]);

        $this->crud->addField([
            'label' => __('Expense'),
            'name' => 'expense',
            'type' => 'number',
            'attributes' => [
                'step' => 0.01,
            ],
        ]);

        $this->crud->addColumn([
            'name' => 'name',
            'label' => __('Name'),
        ]);

        $this->crud->addColumn([
            'name' => 'price',
            'label' => __('Price'),
            'suffix' => '€',
        ]);

        $this->crud->addColumn([
            'name' => 'expense',
            'label' => __('Expense'),
            'suffix' => '€',
        ]);

        $this->crud->addColumn([
            'name' => 'sells',
            'label' => __('Sells'),
            'type' => 'model_function',
            'function_name' => 'getTotalSellsValue',
        ]);

        $this->crud->addColumn([
            'name' => 'profit',
            'label' => __('Profit'),
            'type' => 'model_function',
            'function_name' => 'getTotalProfitValue',
            'suffix' => '€',
        ]);

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['orders' => function ($query) {
            $query->selectRaw('store_product_id, SUM(quantity) as sells')
                ->groupBy('store_product_id');
        }]);

        // add asterisk for fields that are required in StoreProductsRequest
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
