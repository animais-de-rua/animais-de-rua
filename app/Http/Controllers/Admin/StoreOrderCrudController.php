<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreOrderRequest as StoreRequest;
use App\Http\Requests\StoreOrderRequest as UpdateRequest;
use App\Models\StoreOrder;

/**
 * Class StoreOrdersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StoreOrderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StoreOrder');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/store/orders');
        $this->crud->setEntityNameStrings(__('order'), __('orders'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'label' => __('Reference'),
            'name' => 'reference',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Recipient'),
            'name' => 'recipient',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'textarea',
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
            'data_source' => null,
            // 'attributes' => [
            //     'disabled' => 'disabled',
            // ],
        ]);

        $this->crud->addField([
            'name' => 'products',
            'type' => 'products-table',
            'options' => $this->wantsJSON() ? null : api()->storeProductList(),
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'name' => 'notes',
            'type' => 'textarea',
        ]);

        $this->separator(ucfirst(__('shipment')))->afterField('notes');

        // Order sent?
        $id = $this->getEntryID();
        $sent = $id && StoreOrder::find($id)->shipment_date;

        $this->crud->addField([
            'label' => __('Sent'),
            'name' => 'sent',
            'type' => 'checkbox',
            'value' => $sent,
            'attributes' => [
                'order' => 'sent',
            ],
        ]);

        $this->crud->addField([
            'label' => __('Shipment date'),
            'name' => 'shipment_date',
            'type' => 'date',
            'attributes' => [
                'order' => 'details',
                'disabled' => 'disabled',
            ],
        ]);

        $this->crud->addField([
            'label' => __('Expense'),
            'name' => 'expense',
            'type' => 'number',
            'attributes' => [
                'order' => 'details',
                'disabled' => 'disabled',
                'step' => '0.01',
            ],
        ]);

        $this->crud->addColumn([
            'name' => 'reference',
            'label' => __('Reference'),
        ]);

        $this->crud->addColumn([
            'name' => 'recipient',
            'label' => __('Recipient'),
        ]);

        $this->crud->addColumn([
            'name' => 'expense',
            'label' => __('Expense'),
            'suffix' => '€',
        ]);

        $this->crud->addColumn([
            'name' => 'sells',
            'label' => __('Total products'),
            'type' => 'model_function',
            'function_name' => 'getTotalSellsValue',
        ]);

        $this->crud->addColumn([
            'name' => 'user_id',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->addColumn([
            'name' => 'shipment_date',
            'label' => __('Shipment date'),
        ]);

        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();

        // Buttons
        $this->crud->addButtonFromModelFunction('line', 'add_shipment', 'addShipment', 'beginning');

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['products' => function ($query) {
            $query->selectRaw('store_product_id, SUM(quantity) as sells')
                ->groupBy('store_order_id');
        }]);

        // add asterisk for fields that are required in StoreOrdersRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function showDetailsRow($id)
    {
        $order = StoreOrder::select(['id', 'notes'])->with('products')->find($id);

        $products = join('', array_map(function ($product) {
            return "<tr><td>{$product['name']}</td><td class='right'>{$product['pivot']['quantity']}</td><td class='right'>{$product['price']}€</td></tr>";
        }, $order->products->toArray()));

        return "<div style='margin:5px 8px'>
                <table class='order-table'>$products</table>
                <p><i>$order->notes</i></p>
            </div>";
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        $order = StoreOrder::find($request->id);
        $order->products()->detach();

        $products = json_decode($request->products);
        foreach ($products as $product) {
            $order->products()->attach([$product->pivot->store_product_id => (array) $product->pivot]);
        }

        if (!$request->sent) {
            $request->request->set('shipment_date', null);
            $request->request->set('expense', null);
        }

        return parent::updateCrud($request);
    }
}
