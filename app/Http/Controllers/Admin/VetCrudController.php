<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Requests\VetRequest as StoreRequest;
use App\Http\Requests\VetRequest as UpdateRequest;

/**
 * Class VetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VetCrudController extends CrudController
{
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
        $this->crud->setColumns(['name', 'phone', 'url', 'headquarter', 'status']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name')
        ]);

        $this->crud->setColumnDetails('phone', [
            'type' => "phone",
            'label' => __('Phone'),
        ]);

        $this->crud->setColumnDetails('url', [
            'type' => "url",
            'label' => __('Website'),
        ]);

        $this->crud->setColumnDetails('headquarter', [
            'label' => ucfirst(__('headquarter')),
            'type' => "select",
            'entity' => 'headquarter',
            'attribute' => "name",
            'model' => "App\Models\Headquarter"
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status')
        ]);

        // ------ CRUD FIELDS

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name'
        ]);

        $this->crud->addField([
            'label' => __('Email'),
            'name' => 'email',
            'type' => 'email'
        ]);

        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone'
        ]);

        $this->crud->addField([
            'label' => __('Website'),
            'name' => 'url'
        ]);

        /*$this->crud->addField([
            'label' => __('Address'),
            'type' => 'textarea',
            'name' => 'address'
        ]);*/

        $this->crud->addField([
            'label' => ucfirst(__("headquarter")),
            'name' => 'headquarter_id',
            'type' => 'select2',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => 'App\Models\Headquarter'
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status'
        ]);

        $this->crud->addField([
            'label' => __('Location'),
            'type' => 'latlng',
            'name' => 'latlong',
            'map_style' => 'width:100%; height:320px;',
            'google_api_key' => env('GOOGLE_API_KEY'),
            'default_zoom' => '9'
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('treatments')),
            'name' => 'treatments',
            'type' => 'relation_table',
            'route' => '/admin/treatment',
            'columns' => [
                'treatment_type' => [
                    'label' => ucfirst(__('treatment type')),
                    'name' => 'treatment_type',
                    'attribute' => 'name'
                ],
                'process' => [
                    'label' => ucfirst(__('process')),
                    'name' => 'processLink',
                ],
                'expense' => [
                    'label' => __('Expense'),
                    'name' => 'fullExpense',
                ],
                'date' => [
                    'label' => __('Date'),
                    'name' => 'date',
                ]
            ]
        ]);

        // Filtrers
        $this->crud->addFilter([
            'name' => 'headquarter_id',
            'type' => 'select2_multiple',
            'label'=> ucfirst(__("headquarter")),
            'placeholder' => __('Select a headquarter')
        ],
        $this->wantsJSON() ? null : api()->headquarterList(),
        function($values) {
            $this->crud->addClause('whereIn', 'headquarter_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label'=> __("Status"),
            'placeholder' => __('Select a status')
        ],
        EnumHelper::translate('vet.status'),
        function($values) {
            $this->crud->addClause('whereIn', 'status', json_decode($values));
        });

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
