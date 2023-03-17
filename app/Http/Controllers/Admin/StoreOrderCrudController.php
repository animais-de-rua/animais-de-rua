<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
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

        $attributeDisabled = is('admin', 'store orders') ? [] : [
            'disabled' => 'disabled',
        ];

        $this->crud->addField([
            'label' => __('Reference'),
            'name' => 'reference',
            'type' => 'text',
            'attributes' => $attributeDisabled,
        ]);

        $this->crud->addField([
            'label' => __('Recipient'),
            'name' => 'recipient',
            'type' => 'text',
            'attributes' => $attributeDisabled,
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'textarea',
            'attributes' => $attributeDisabled,
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
            'data_source' => url('admin/user/ajax/search/' . User::ROLE_STORE . '/' . User::PERMISSION_STORE_SHIPPMENTS),
            'attributes' => $attributeDisabled,
        ]);

        $this->crud->addField([
            'name' => 'products',
            'type' => 'products-table',
            'options' => $this->wantsJSON() ? null : api()->storeProductListFull(),
            'attributes' => $attributeDisabled,
            'readonly' => !is('admin', 'store orders'),
        ]);

        $this->crud->addField([
            'label' => __('Payment'),
            'type' => 'enum',
            'name' => 'payment',
            'attributes' => $attributeDisabled,
        ]);

        $this->crud->addField([
            'label' => __('Receipt'),
            'name' => 'receipt',
            'type' => 'text',
            'attributes' => $attributeDisabled,
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'name' => 'notes',
            'type' => 'textarea',
            'attributes' => [
                'style' => 'height: 140px;',
            ],
        ]);

        $this->separator(ucfirst(__('shipment')))->afterField('notes');

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
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
            ],
        ]);

        $this->crud->addField([
            'label' => __('Expense'),
            'name' => 'expense',
            'type' => 'number',
            'attributes' => [
                'order' => 'details',
                'step' => '0.01',
            ],
        ]);

        $this->crud->addField([
            'label' => __('Invoice'),
            'name' => 'invoice',
            'type' => 'text',
            'attributes' => [
                'order' => 'details',
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
            'name' => 'status',
            'type' => 'trans',
            'label' => __('Status'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'type' => 'date',
            'label' => __('Date'),
        ]);

        $this->crud->addColumn([
            'name' => 'shipment_date',
            'label' => __('Shipment date'),
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
            'name' => 'total',
            'label' => __('Total'),
            'type' => 'model_function',
            'function_name' => 'getTotalValue',
            'suffix' => '€',
        ]);

        $this->crud->addColumn([
            'name' => 'user_id',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->addColumn([
            'name' => 'payment',
            'type' => 'trans',
            'label' => __('Payment'),
        ]);

        $this->crud->addColumn([
            'name' => 'invoice',
            'type' => 'text',
            'label' => __('Invoice'),
        ]);

        $this->crud->addColumn([
            'name' => 'address',
            'label' => __('Address'),
            'limit' => 192,
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'receipt',
            'label' => __('Receipt'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'notes',
            'label' => __('Notes'),
            'visibleInTable' => false,
        ]);

        // Filtrers
        if (is('admin', 'store orders')) {
            $this->crud->addFilter([
                'name' => 'user',
                'type' => 'select2_ajax',
                'label' => ucfirst(__('volunteer')),
                'placeholder' => __('Select a volunteer'),
            ],
                url('admin/user/ajax/filter/' . User::ROLE_STORE . '/' . User::PERMISSION_STORE_SHIPPMENTS),
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
                $this->crud->addClause('whereHas', 'products', function ($query) use ($value) {
                    $query->where('store_product_id', $value);
                })->get();
            });

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
                $this->crud->query->whereRaw('shipment_date >= ? AND shipment_date <= ?', [$dates->from, $dates->to]);
            });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            EnumHelper::translate('store.order'),
            function ($values) {
                $this->crud->addClause('whereIn', 'status', json_decode($values));
            });

        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['products' => function ($query) {
            $query->selectRaw('store_product_id, SUM(quantity) as sells, SUM(price * quantity - discount) as total')
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

        // Button to open all details
        $this->crud->addButtonFromModelFunction('top', 'open_all', 'openAll', 'end');

        // add asterisk for fields that are required in StoreOrdersRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function showDetailsRow($id)
    {
        $order = StoreOrder::select(['id', 'notes', 'receipt', 'invoice', 'recipient', 'address'])->with('products')->find($id);

        $totals = [0, 0, 0, 0];
        $products = '';

        foreach ($order->products->toArray() as $product) {
            $quantity = $product['pivot']['quantity'];
            $price = $product['price'] * $product['pivot']['quantity'];
            $discount = $product['pivot']['discount'];
            $total = $price - $discount;

            $totals[0] += $quantity;
            $totals[1] += $price;
            $totals[2] += $discount;
            $totals[3] += $total;

            $products .= "<tr>
                <td>{$product['name']}</td>
                <td class='right'>{$quantity}</td>
                <td class='right'>{$price}€</td>
                <td class='right'>{$discount}€</td>
                <td class='right'>{$total}€</td>
                </tr>";
        }

        return "<div style='margin:5px 8px'>
                <table class='order-table' style='margin-bottom: 8px;'>
                <tr style='border-bottom: 2px solid #ccc'>
                    <th>" . __('Name') . "</th>
                    <th class='right'>" . __('Quantity') . "</th>
                    <th class='right'>" . __('Price') . "</th>
                    <th class='right'>" . __('Discount') . "</th>
                    <th class='right'>" . __('Total') . "</th>
                </tr>
                $products
                <tr style='border-top: 2px solid #ccc'>
                    <th>" . __('Total') . "</th>
                    <th class='right'>{$totals[0]}</th>
                    <th class='right'>" . number_format((float) $totals[1], 2, '.', '') . "€</th>
                    <th class='right'>" . number_format((float) $totals[2], 2, '.', '') . "€</th>
                    <th class='right'>" . number_format((float) $totals[3], 2, '.', '') . '€</th>
                </tr>
                </table>
                <p>
                    <b>' . __('Receipt') . ':</b> ' . ($order->receipt ? "<code>$order->receipt</code>" : '') . '<br />
                    <b>' . __('Invoice') . ':</b> ' . ($order->invoice ? "<code>$order->invoice</code>" : '') . '
                </p>
                <p>
                    <b>' . __('Recipient') . ':</b> ' . ($order->recipient ? "$order->recipient" : '') . '<br />
                    <b>' . __('Address') . ':</b> ' . ($order->address ? "$order->address" : '') . '
                </p>
                <b>' . __('Notes') . ":</b>
                <p style='white-space: pre-wrap;'>$order->notes</p>
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

        // Clean up request in case the user has not the required permissions
        if (!is('admin', 'store orders')) {
            $request = new \Illuminate\Http\Request([
                'id' => $request->id,
                'status' => $request->status,
                'shipment_date' => $request->shipment_date,
                'expense' => $request->expense,
                'invoice' => $request->invoice,
            ]);
        }

        // Clean up shippment info
        if ($request->status != 'shipped') {
            $request->merge([
                'shipment_date' => null,
                'invoice' => null,
                'expense' => 0,
            ]);
        }

        return parent::updateCrud($request);
    }

    private function inserProductRelation($id, $request)
    {
        $order = StoreOrder::find($id);
        $order->products()->detach();

        $products = json_decode($request->products);

        $store_product_ids = [];

        foreach ($products as $product) {
            // Ignore repeated entries
            if (isset($product->pivot) && isset($product->pivot->store_product_id) && !in_array($product->pivot->store_product_id, $store_product_ids)) {
                array_push($store_product_ids, $product->pivot->store_product_id);

                // Add to database
                $order->products()->attach([$product->pivot->store_product_id => (array) $product->pivot]);
            }
        }
    }
}
