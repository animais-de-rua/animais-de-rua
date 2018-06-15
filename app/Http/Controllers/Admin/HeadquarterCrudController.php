<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\HeadquarterRequest as StoreRequest;
use App\Http\Requests\HeadquarterRequest as UpdateRequest;

class HeadquarterCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Headquarter');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/headquarter');
        $this->crud->setEntityNameStrings(__('headquarter'), __('headquarters'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        //$this->crud->setFromDb();

        // ------ CRUD FIELDS
        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
            'type' => 'text'
        ]);
        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'address'
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
            'label' => __('E-Mail Address'),
            'name' => 'mail',
            'type' => 'email',
        ]);
        $this->crud->addField([
            'label' => __('Description'),
            'name' => 'description',
            'type' => 'textarea'
        ]);
        $this->crud->addField([
            'label' => __("Location"),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => app('App\Http\Controllers\Admin\TerritoryCrudController')->ajax_list(),
            'allows_null' => true,
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'address', 'phone']);

        // ------ CRUD ACCESS
        $this->crud->denyAccess(['delete']);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);;
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
