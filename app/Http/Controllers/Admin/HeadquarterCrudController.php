<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\HeadquarterRequest as StoreRequest;
use App\Http\Requests\HeadquarterRequest as UpdateRequest;
use App\Models\Territory;
use Illuminate\Http\Request;

class HeadquarterCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Headquarter');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/headquarter');
        $this->crud->setEntityNameStrings(__('headquarter'), __('headquarters'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'address',
        ]);
        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
            'type' => 'text',
            'attributes' => [
                'type' => 'tel',
                'maxlength' => '14',
            ],
        ]);
        $this->crud->addField([
            'label' => __('E-Mail Address'),
            'name' => 'mail',
            'type' => 'email',
        ]);
        $this->crud->addField([
            'label' => __('Description'),
            'name' => 'description',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'label' => __('Acting Zone'),
            'type' => 'select2_multiple_data_source',
            'name' => 'territories',
            'attribute' => 'name',
            'model' => api()->territorySearch(Territory::DISTRITO | Territory::CONCELHO, new Request),
            'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => __('Range Zone'),
            'type' => 'select2_multiple_data_source',
            'name' => 'territories_range',
            'attribute' => 'name',
            'model' => api()->territorySearch(Territory::DISTRITO | Territory::CONCELHO, new Request),
            'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('active')),
            'name' => 'active',
            'type' => 'checkbox',
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'address', 'phone', 'active']);

        $this->crud->setColumnDetails('name', [
            'name' => 'name',
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('address', [
            'name' => 'address',
            'label' => __('Address'),
        ]);

        $this->crud->setColumnDetails('phone', [
            'name' => 'phone',
            'label' => __('Phone'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => __('Description'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'label' => ucfirst(__('active')),
            'name' => 'active',
            'type' => 'check',
        ]);

        // ------ CRUD ACCESS
        if (! is('admin')) {
            $this->crud->denyAccess(['list', 'create', 'update', 'delete']);
        }

        $this->crud->denyAccess(['delete']);

        // ------ ADVANCED QUERIES

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

    public function sync()
    {
        \Cache::forget('headquarters');
        \Cache::forget('headquarters_territories_acting');
    }
}
