<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\PartnerCategoryRequest as StoreRequest;
use App\Http\Requests\PartnerCategoryRequest as UpdateRequest;

/**
 * Class PartnerCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PartnerCategoryCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PartnerCategory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/partner-category');
        $this->crud->setEntityNameStrings(__('partner category'), __('partner categories'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->setColumns(['name', 'description']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('description', [
            'label' => __('Description'),
        ]);

        // ------ CRUD FIELDS
        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Description'),
            'name' => 'description',
            'type' => 'wysiwyg',
        ]);

        // ------ CRUD ACCESS
        if (!is(['admin', 'friend card'])) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete']);
        }

        // ------ ADVANCED QUERIES

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in PartnerCategoryRequest
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
        \Cache::forget('partners_categories');
    }
}
