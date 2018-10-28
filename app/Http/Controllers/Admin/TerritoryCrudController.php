<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\TerritoryRequest as StoreRequest;
use App\Http\Requests\TerritoryRequest as UpdateRequest;

class TerritoryCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Territory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/territory');
        $this->crud->setEntityNameStrings(__('territory'), __('territories'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->setColumns(['name', 'sf']);

        $this->crud->setColumnDetails('sf', [
            'label' => 'Código SF',
        ]);

        // ------ CRUD BUTTONS
        $this->crud->removeAllButtons();

        // ------ CRUD ACCESS
        $this->crud->allowAccess(['list']);
        $this->crud->denyAccess(['create', 'update', 'reorder', 'delete']);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // Filtrers
        $this->crud->addFilter([
            'name' => 'level',
            'type' => 'select2',
            'label' => ucfirst(__('territory')),
        ],
            EnumHelper::translate('territory.levels'),
            function ($value) {
                $this->crud->addClause('where', 'level', $value);
            });

        $this->crud->addFilter([
            'name' => 'district',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('district')),
            'placeholder' => __('Select a district'),
        ],
            url('admin/territory/ajax/filter/1'),
            function ($value) {
                $this->crud->addClause('where', 'parent_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'county',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('county')),
            'placeholder' => __('Select a county'),
        ],
            url('admin/territory/ajax/filter/2'),
            function ($value) {
                $this->crud->addClause('where', 'parent_id', $value);
            });

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
