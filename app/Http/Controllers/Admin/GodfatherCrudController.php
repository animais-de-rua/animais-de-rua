<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GodfatherRequest as StoreRequest;
use App\Http\Requests\GodfatherRequest as UpdateRequest;
use App\Models\Godfather;

/**
 * Class GodfatherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GodfatherCrudController extends CrudController
{

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Godfather');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/godfather');
        $this->crud->setEntityNameStrings(__('godfather'), __('godfathers'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'label' => __('Alias'),
            'name' => 'alias',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'label' => __('Email'),
            'name' => 'email',
            'type' => 'email'
        ]);
        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
            'type' => 'text',
            'attributes' => [
                'type' => 'tel',
                'maxlength' => '14'
            ]
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territory')),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => api()->territoryList(),
            'allows_null' => true
        ]);

        $this->separator();

        $this->crud->addField([
            'label' => ucfirst(__('donations')),
            'name' => 'donations',
            'type' => 'relation_table',
            'route' => '/admin/donation',
            'columns' => [
                'name' => [
                    'label' => ucfirst(__('process')),
                    'name' => 'processLink'
                ],
                'value' => [
                    'label' => __('Value'),
                    'name' => 'fullValue'
                ],
                'date' => [
                    'label' => __('Date'),
                    'name' => 'date'
                ]
            ]
        ]);

        $this->crud->addField([
            'label' => __('Stats'),
            'name' => 'stats',
            'type' => 'stats',
            'rows' => [
                'donated' => [
                    'label' => __('Total Donated'),
                    'value' => 'getTotalDonatedStats'
                ],
                'donations' => [
                    'label' => __('Total Donations'),
                    'value' => 'getTotalDonationsStats'
                ]
            ]
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'email', 'phone', 'donations']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name')
        ]);

        $this->crud->setColumnDetails('email', [
            'label' => __('Email'),
            'type' => 'email'
        ]);

        $this->crud->setColumnDetails('phone', [
            'label' => __('Phone')
        ]);

        $this->crud->setColumnDetails('donations', [
            'name' => 'donations',
            'label' => __('Total Donated'),
            'type' => 'model_function',
            'function_name' => 'getTotalDonatedValue'
        ]);

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['donations' => function ($query) {
            $query->selectRaw('godfather_id, sum(value) as total')
                ->groupBy(['godfather_id']);
        }]);

        // Add asterisk for fields that are required
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Validate email
        $request->validate(['email' => 'required|email|unique:godfathers,email']);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        // Validate email with except id
        $request->validate(['email' => 'required|email|unique:godfathers,email,' . $request->id]);

        return parent::updateCrud($request);
    }
}
