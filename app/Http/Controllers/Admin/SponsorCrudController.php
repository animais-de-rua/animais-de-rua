<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\SponsorRequest as StoreRequest;
use App\Http\Requests\SponsorRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

/**
 * Class SponsorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SponsorCrudController extends CrudController
{
    use Permissions;
    use ReorderOperation { saveReorder as crudSaveReorder; }

    public function saveReorder()
    {
        $result = $this->crudSaveReorder();
        $this->sync();
        return $result;
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Sponsor');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/sponsor');
        $this->crud->setEntityNameStrings(__('sponsor'), __('sponsors'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addField([
            'label' => __('Name'),
            'type' => 'text',
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('URL'),
            'type' => 'url',
            'name' => 'url',
        ]);

        $this->crud->addField([
            'label' => __('Image'),
            'name' => 'image',
            'type' => 'image',
            'upload' => true,
            'crop' => true,
            'disk' => 'uploads',
        ]);

        $this->crud->addColumns(['image', 'name', 'url']);

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

        $this->crud->setColumnDetails('url', [
            'name' => 'url',
            'type' => 'url',
            'label' => __('URL'),
        ]);

        // ------ CRUD ACCESS
        if (!is('admin')) {
            $this->crud->denyAccess(['list', 'create', 'update', 'delete']);
        }

        // add asterisk for fields that are required in CampaignRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->setValidation(StoreRequest::class);
        $this->crud->setValidation(UpdateRequest::class);

        $this->crud->enableReorder('name', 1);
        $this->crud->allowAccess('reorder');
    }

    public function store()
    {
        return parent::store();
    }

    public function update()
    {
        return parent::update();
    }

    public function sync()
    {
        \Cache::forget('sponsors');
    }
}
