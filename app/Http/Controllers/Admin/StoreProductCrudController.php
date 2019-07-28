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
            'label' => __('Price') . ' (' . __('with :vat% VAT', ['vat' => \Config::get('settings.vat')]) . ')',
            'name' => 'price',
            'type' => 'number',
            'suffix' => '€',
            'attributes' => [
                'step' => 0.01,
            ],
        ]);

        $this->crud->addField([
            'label' => __('Price') . ' (' . __('no VAT') . ')',
            'name' => 'price_no_vat',
            'type' => 'number_vat',
            'base' => 'price',
            'suffix' => '€',
            'attributes' => [
                'step' => 0.01,
                'readonly' => true,
            ],
        ]);

        $this->crud->addField([
            'label' => __('Expense'),
            'name' => 'expense',
            'type' => 'number',
            'suffix' => '€',
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

        if (is('admin')) {
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
                'name' => 'shipment_expense',
                'label' => __('Shipment Expense'),
                'type' => 'model_function',
                'function_name' => 'getShipmentExpenseValue',
                'suffix' => '€',
            ]);

            $this->crud->addColumn([
                'name' => 'profit',
                'label' => __('Profit'),
                'type' => 'model_function',
                'function_name' => 'getTotalProfitValue',
                'suffix' => '€',
            ]);
        }

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['orders' => function ($query) {
            $query->selectRaw('store_product_id, SUM(quantity) as sells, SUM(expenses.expense_by_product * store_orders_products.quantity) as shipment_expense')
                ->join(
                    // This query shares the shipment expense by each product for each order
                    \DB::raw('(SELECT store_orders.id, store_orders.expense / SUM(quantity) as expense_by_product
                        FROM store_orders, store_orders_products
                        WHERE store_orders_products.store_order_id = store_orders.id AND store_orders.expense > 0
                        GROUP BY store_orders.id) expenses'),
                    'store_orders.id', '=', 'expenses.id')
                ->groupBy('store_product_id');
        }]);

        // ------ CRUD ACCESS
        if (!is(['admin', 'store'])) {
            $this->crud->denyAccess(['list']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['show', 'create', 'update', 'delete']);
        }

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
