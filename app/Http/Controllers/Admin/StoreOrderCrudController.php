<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreOrderRequest as StoreRequest;
use App\Http\Requests\StoreOrderRequest as UpdateRequest;
use App\Models\StoreOrder;
use App\User;

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

        $attributes = is('admin', 'store orders') ? [] : [
            'disabled' => 'disabled',
        ];

        $this->crud->addField([
            'label' => __('Reference'),
            'name' => 'reference',
            'type' => 'text',
            'attributes' => $attributes,
        ]);

        $this->crud->addField([
            'label' => __('Recipient'),
            'name' => 'recipient',
            'type' => 'text',
            'attributes' => $attributes,
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'textarea',
            'attributes' => $attributes,
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
            'data_source' => url('admin/user/ajax/search/' . User::VOLUNTEER),
            'attributes' => $attributes,
        ]);

        $this->crud->addField([
            'name' => 'products',
            'type' => 'products-table',
            'options' => $this->wantsJSON() ? null : api()->storeProductList(),
            'attributes' => $attributes,
            'readonly' => !is('admin', 'store orders'),
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'name' => 'notes',
            'type' => 'textarea',
            'attributes' => $attributes,
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

        // Filtrers
        if (is('admin')) {
            $this->crud->addFilter([
                'name' => 'user',
                'type' => 'select2_ajax',
                'label' => ucfirst(__('volunteer')),
                'placeholder' => __('Select a volunteer'),
            ],
                url('admin/user/ajax/filter/' . User::VOLUNTEER),
                function ($value) {
                    $this->crud->addClause('where', 'user_id', $value);
                });
        }

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'from_to',
            'label' => __('Shipment date'),
            'format' => 'DD/MM/YYYY',
            'firstDay' => 1,
        ],
            false,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->query->whereRaw('shipment_date >= ? AND shipment_date <= DATE_ADD(?, INTERVAL 1 DAY)', [$dates->from, $dates->to]);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'sent',
            'label' => __('Sent'),
        ],
            false,
            function ($value) {
                if ($value) {
                    $this->crud->addClause('where', 'shipment_date', '>', '0');
                }
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'not_sent',
            'label' => __('Not sent'),
        ],
            false,
            function ($value) {
                if ($value) {
                    $this->crud->addClause('where', 'shipment_date', null);
                }
            });

        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['products' => function ($query) {
            $query->selectRaw('store_product_id, SUM(quantity) as sells')
                ->groupBy('store_order_id');
        }, 'user']);

        // ------ CRUD ACCESS
        if (!is('admin')) {
            $this->crud->denyAccess(['delete']);
        }

        if (!is(['admin', 'store'])) {
            $this->crud->denyAccess('list');
        }

        if (!is('admin', ['store orders', 'store shippments'])) {
            $this->crud->denyAccess(['show', 'create', 'update']);
        }

        if (!is('admin', 'store orders')) {
            $this->crud->denyAccess(['create']);

            // Filter by user
            $this->crud->addClause('where', 'user_id', backpack_user()->id);
        }

        // Add Shipment button
        if (is('admin', ['store shippments'])) {
            $this->crud->addButtonFromModelFunction('line', 'add_shipment', 'addShipment', 'beginning');
        }

        $this->crud->addClause('orderBy', 'id', 'DESC');
        // $this->crud->addClause('orderBy', 'shipment_date', 'ASC');

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
        $result = parent::storeCrud($request);

        $this->inserProductRelation($this->crud->entry->id, $request);

        return $result;
    }

    public function update(UpdateRequest $request)
    {
        $this->inserProductRelation($request->id, $request);

        return parent::updateCrud($request);
    }

    private function inserProductRelation($id, $request)
    {
        $order = StoreOrder::find($id);
        $order->products()->detach();

        $products = json_decode($request->products);
        foreach ($products as $product) {
            $order->products()->attach([$product->pivot->store_product_id => (array) $product->pivot]);
        }
    }
}
