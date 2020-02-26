<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\CampaignRequest as StoreRequest;
use App\Http\Requests\CampaignRequest as UpdateRequest;

/**
 * Class CampaignCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CampaignCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Campaign');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/campaign');
        $this->crud->setEntityNameStrings(__('campaign'), __('campaigns'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['name', 'introduction', 'description', 'image']);

        $this->crud->addField([
            'label' => __('Name'),
            'type' => 'text',
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Introduction'),
            'type' => 'wysiwyg',
            'name' => 'introduction',
        ]);

        $this->crud->addField([
            'label' => __('Description'),
            'type' => 'wysiwyg',
            'name' => 'description',
        ]);

        $this->crud->addField([
            'label' => __('Image'),
            'name' => 'image',
            'type' => 'image',
            'upload' => true,
            'crop' => true,
            'disk' => 'uploads',
        ]);

        $this->crud->addColumns(['image', 'name', 'introduction']);

        $this->crud->setColumnDetails('image', [
            'name' => 'image',
            'label' => __('Image'),
            'type' => 'image',
            'prefix' => 'uploads/',
            'height' => '50px',
        ]);

        $this->crud->setColumnDetails('name', [
            'name' => 'name',
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('introduction', [
            'name' => 'introduction',
            'label' => __('Introduction'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => __('Description'),
            'visibleInTable' => false,
        ]);

        // ------ CRUD ACCESS
        if (!is('admin', 'website')) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete']);
        }

        // ------ ADVANCED QUERIES

        // add asterisk for fields that are required in CampaignRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->enableReorder('name', 1);
        $this->crud->allowAccess('reorder');
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
        \Cache::forget('campaigns');
    }
}
