<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\VetRequest as StoreRequest;
use App\Http\Requests\VetRequest as UpdateRequest;

/**
 * Class VetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VetCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Vet');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/vet');
        $this->crud->setEntityNameStrings(__('vet'), __('vets'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->setColumns(['id', 'name', 'phone', 'url', 'headquarter', 'status', 'total_expenses', 'total_operations']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('phone', [
            'type' => 'tel',
            'label' => __('Phone'),
        ]);

        $this->crud->setColumnDetails('url', [
            'type' => 'url',
            'label' => __('Website'),
        ]);

        $this->crud->setColumnDetails('headquarter', [
            'label' => ucfirst(__('headquarter')),
            'type' => 'select',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => "App\Models\Headquarter",
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status'),
        ]);

        $this->crud->setColumnDetails('total_expenses', [
            'name' => 'total_expenses',
            'label' => __('Total Expenses'),
            'type' => 'model_function',
            'function_name' => 'getTotalExpensesValue',
        ]);

        $this->crud->setColumnDetails('total_operations', [
            'name' => 'total_operations',
            'label' => __('Total Operations'),
            'type' => 'model_function',
            'function_name' => 'getTotalOperationsValue',
        ]);

        // ------ CRUD FIELDS

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Email'),
            'name' => 'email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
        ]);

        $this->crud->addField([
            'label' => __('Website'),
            'name' => 'url',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('headquarter')),
            'name' => 'headquarter_id',
            'type' => 'select2',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => 'App\Models\Headquarter',
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
        ]);

        $this->crud->addField([
            'label' => __('Location'),
            'type' => 'latlng',
            'name' => 'latlong',
            'map_style' => 'width:100%; height:320px;',
            'google_api_key' => env('GOOGLE_API_KEY'),
            'default_zoom' => '9',
        ]);

        $this->crud->addField([
            'label' => __('Stats'),
            'name' => 'stats',
            'type' => 'stats',
            'rows' => [
                'expenses' => [
                    'label' => __('Total Expenses'),
                    'value' => 'getTotalExpensesStats',
                ],
                'operations' => [
                    'label' => __('Total Operations'),
                    'value' => 'getTotalOperationsStats',
                ],
            ],
        ]);

        // Filtrers
        $this->crud->addFilter([
            'name' => 'headquarter_id',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('headquarter')),
            'placeholder' => __('Select a headquarter'),
        ],
            $this->wantsJSON() ? null : api()->headquarterList(),
            function ($values) {
                $this->crud->addClause('whereIn', 'headquarter_id', json_decode($values));
            });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            EnumHelper::translate('vet.status'),
            function ($values) {
                $this->crud->addClause('whereIn', 'status', json_decode($values));
            });

        $this->crud->addFilter([
            'name' => 'total_expenses',
            'type' => 'range',
            'label' => __('Total Expenses') . ' €',
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query->whereHas('treatments', function ($query) use ($range) {
                    $query->selectRaw('vet_id, sum(expense) as total_expenses')
                        ->groupBy('vet_id');

                    if (is_numeric($range->from)) {
                        $query->having('total_expenses', '>=', $range->from);
                    }

                    if (is_numeric($range->to)) {
                        $query->having('total_expenses', '<=', $range->to);
                    }

                });
            });

        $this->crud->addFilter([
            'name' => 'total_operations',
            'type' => 'range',
            'label' => __('Total Operations'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query->whereHas('treatments', function ($query) use ($range) {
                    $query->selectRaw('vet_id, count(*) as total_operations')
                        ->groupBy('vet_id');

                    if (is_numeric($range->from)) {
                        $query->having('total_operations', '>=', $range->from);
                    }

                    if (is_numeric($range->to)) {
                        $query->having('total_operations', '<=', $range->to);
                    }

                });
            });

        // ------ CRUD ACCESS
        if (!is('admin', 'vets')) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete']);
        }

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['treatments' => function ($query) {
            $query->selectRaw('vet_id, sum(expense) as total_expenses, count(*) as total_operations')
                ->groupBy('vet_id');
        }]);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // Add asterisk for fields that are required
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
