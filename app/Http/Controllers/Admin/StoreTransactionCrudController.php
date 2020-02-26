<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreTransactionRequest as StoreRequest;
use App\Http\Requests\StoreTransactionRequest as UpdateRequest;
use App\User;
use DB;

/**
 * Class StoreTransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StoreTransactionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StoreTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/store/user/transaction');
        $this->crud->setEntityNameStrings(__('transaction'), __('transactions'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ----------
        // Columns
        $this->crud->addColumns(['id', 'user_id', 'type', 'invoice', 'description']);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->setColumnDetails('type', [
            'type' => 'trans',
            'label' => __('Value'),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getAmountColorize',
        ]);

        $this->crud->setColumnDetails('invoice', [
            'type' => 'text',
            'label' => __('Invoice'),
        ]);

        $this->crud->setColumnDetails('description', [
            'type' => 'text',
            'label' => __('Description'),
        ]);

        $this->crud->addColumn([
            'name' => 'notes',
            'label' => __('Notes'),
            'visibleInTable' => false,
        ]);

        // ----------
        // Fields
        $this->crud->addFields(['description', 'user_id', 'amount', 'invoice', 'notes']);

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
            'label' => __('Value'),
            'name' => 'amount',
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
            'label' => __('Invoice'),
            'name' => 'invoice',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'type' => 'textarea',
            'name' => 'notes',
        ]);

        // ----------
        // Filters
        if (is(['admin'], ['store transaction'])) {
            $this->crud->addFilter([
                'name' => 'user',
                'type' => 'select2_ajax',
                'label' => ucfirst(__('volunteer')),
                'placeholder' => __('Select a volunteer'),
            ],
                url('admin/user/ajax/filter/' . User::ROLE_STORE),
                function ($value) {
                    $this->crud->addClause('where', 'user_id', $value);
                });
        }

        $this->crud->addFilter([
            'name' => 'number',
            'type' => 'range',
            'label' => sprintf('%s (%s)', __('Amount'), '€'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'amount', '>=', (float) $range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'amount', '<=', (float) $range->to);
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
                    $sent_query = 'SELECT user_id as s_user_id, SUM(expense) as expense FROM `store_orders` GROUP BY user_id';

                    $this->crud->query->leftjoin(DB::raw("($sent_query) sent"), function ($join) {
                        $join->on('sent.s_user_id', '=', 'store_transactions.user_id');
                    });

                    $this->crud->groupBy('user_id');

                    $this->crud->query->selectRaw('(SUM(amount) - IFNULL(expense, 0)) as amount');

                    $this->crud->denyAccess(['update', 'delete']);
                }
            });

        $this->crud->addClause('with', ['user']);

        $this->crud->addClause('orderBy', 'store_transactions.id', 'DESC');

        // Permissions
        if (!is(['admin', 'store'], 'store transaction')) {
            $this->crud->denyAccess(['create']);
        }

        if (!is(['admin'], ['store transaction'])) {
            $this->crud->denyAccess(['update', 'delete']);

            $this->crud->addClause('where', 'user_id', backpack_user()->id);
        }

        if (!is('admin')) {
            $this->crud->removeField('user_id');
        }

        // add asterisk for fields that are required in StoreTransactionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        if (!is(['admin'])) {
            $request->merge(['user_id' => backpack_user()->id]);
        }

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
