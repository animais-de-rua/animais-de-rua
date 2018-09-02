<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Helpers\EnumHelper;
use App\Models\FriendCardModality;
use App\Http\Requests\FriendCardModalityRequest as StoreRequest;
use App\Http\Requests\FriendCardModalityRequest as UpdateRequest;
use App\User;

/**
 * Class FriendCardModalityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FriendCardModalityCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\FriendCardModality');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/friend-card-modality');
        $this->crud->setEntityNameStrings(__('friend card modality'), __('friend card modalities'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['name', 'description', 'paypal_code', 'amount', 'type']);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name'
        ]);

        $this->crud->addField([
            'label' => __('Description'),
            'type' => 'wysiwyg',
            'name' => 'description'
        ]);

        $this->crud->addField([
            'label' => __('Paypal code'),
            'name' => 'paypal_code'
        ]);

        $this->crud->addField([
            'label' => __('Value'),
            'name' => 'amount',
            'type' => 'number',
            'default' => 0,
            'suffix' => '€',
            'attributes' => ['min' => 0, 'max' => 1000000],
        ]);

        $this->crud->addField([
            'label' => __('Type'),
            'name' => 'type',
            'type' => 'enum'
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'amount', 'type']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('amount', [
            'label' => __('Value'),
            'suffix' => '€',
        ]);

        $this->crud->setColumnDetails('type', [
            'type' => 'trans',
            'label' => __('Type')
        ]);

        // add asterisk for fields that are required in FriendCardModalityRequest
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
