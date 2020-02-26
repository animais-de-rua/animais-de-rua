<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\FriendCardModalityRequest as StoreRequest;
use App\Http\Requests\FriendCardModalityRequest as UpdateRequest;
use App\Models\FriendCardModality;

/**
 * Class FriendCardModalityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FriendCardModalityCrudController extends CrudController
{
    use Permissions;

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
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Description'),
            'type' => 'wysiwyg',
            'name' => 'description',
        ]);

        $this->crud->addField([
            'label' => __('Paypal code'),
            'name' => 'paypal_code',
        ]);

        $this->crud->addField([
            'label' => __('Value'),
            'name' => 'amount',
            'type' => 'number',
            'default' => 0,
            'suffix' => '€',
            'attributes' => ['min' => 0, 'max' => 1000000, 'step' => .01],
        ]);

        $this->crud->addField([
            'label' => __('Type'),
            'name' => 'type',
            'type' => 'enum',
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
            'label' => __('Type'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => __('Description'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'paypal_code',
            'label' => __('Paypal code'),
            'visibleInTable' => false,
        ]);

        // ------ CRUD ACCESS
        if (!is('admin')) {
            $this->crud->denyAccess(['list', 'create', 'update', 'delete']);
        }

        // ------ ADVANCED QUERIES

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

    public function sync()
    {
        \Cache::forget('friend_card_modalities');
    }
}
