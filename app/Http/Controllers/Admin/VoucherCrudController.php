<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VoucherRequest as StoreRequest;
use App\Http\Requests\VoucherRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

/**
 * Class VoucherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VoucherCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Voucher');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/store/voucher');
        $this->crud->setEntityNameStrings(__('voucher'), __('vouchers'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ----------
        // Columns
        $this->crud->setColumns(['reference', 'voucher', 'value', 'client_name', 'client_email', 'expiration', 'status']);

        $this->crud->addColumn([
            'label' => __('Reference'),
            'name' => 'reference',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'label' => __('Voucher'),
            'name' => 'voucher',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'label' => __('Value'),
            'name' => 'value',
            'type' => 'number',
            'suffix' => '€',
            'attributes' => [
                'step' => '0.01',
            ],
        ]);

        $this->crud->addColumn([
            'label' => __('Client Name'),
            'name' => 'client_name',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'label' => __('Client Email'),
            'name' => 'client_email',
            'type' => 'email',
        ]);

        $this->crud->addColumn([
            'label' => __('Expiration Date'),
            'name' => 'expiration',
            'type' => 'date',
        ]);

        $this->crud->addColumn([
            'label' => __('Status'),
            'name' => 'status',
            'type' => 'trans',
        ]);

        // ----------
        // Fields
        $this->crud->addFields(['reference', 'voucher', 'value', 'client_name', 'client_email', 'expiration', 'status']);

        $this->crud->addField([
            'label' => __('Reference'),
            'name' => 'reference',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Voucher'),
            'name' => 'voucher',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Value'),
            'name' => 'value',
            'type' => 'number',
        ]);

        $this->crud->addField([
            'label' => __('Client Name'),
            'name' => 'client_name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Client Email'),
            'name' => 'client_email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'label' => __('Expiration Date'),
            'name' => 'expiration',
            'type' => 'date',
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'name' => 'status',
            'type' => 'enum',
        ]);

        // add asterisk for fields that are required in VoucherRequest
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
