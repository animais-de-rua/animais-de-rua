<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProtocolRequest as StoreRequest;
use App\Http\Requests\ProtocolRequest as UpdateRequest;
use App\User;

/**
 * Class ProtocolCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProtocolCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Protocol');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/protocol');
        $this->crud->setEntityNameStrings(__('protocol'), __('protocols'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->setColumns(['council', 'name', 'territory', 'process', 'user']);

        $this->crud->setColumnDetails('council', [
            'label' => 'ID ' . ucfirst(__('council'))
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name')
        ]);

        $this->crud->setColumnDetails('territory', [
            'label' => ucfirst(__('territory')),
            'type' => 'select',
            'name' => 'territory',
            'entity' => 'territory',
            'attribute' => 'name',
            'model' => "App\Models\Territory"
        ]);

        $this->crud->setColumnDetails('process', [
            'name' => 'process',
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute'
        ]);

        $this->crud->setColumnDetails('user', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute'
        ]);

        $this->crud->addFields(['council', 'name', 'email', 'phone', 'address', 'description', 'territory_id', 'process_id']);

        $this->crud->addField([
            'label' => ucfirst(__('council')),
            'name' => 'council'
        ]);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name'
        ]);

        $this->crud->addField([
            'label' => __('Email'),
            'name' => 'email',
            'type' => 'email'
        ]);

        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone'
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => __('Description'),
            'name' => 'description',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territory')),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => $this->wantsJSON() ? null : api()->territoryList(),
            'allows_null' => true
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('process')),
            'name' => 'process_id',
            'type' => 'select2_from_ajax',
            'entity' => 'process',
            'attribute' => 'detail',
            'model' => '\App\Models\Process',
            'data_source' => url('admin/process/ajax/search'),
            'placeholder' => __('Select a process'),
            'minimum_input_length' => 2,
            'default' => \Request::has('process') ?? false
        ]);

        // Filters
        $this->crud->addFilter([
            'name' => 'territory',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('territory')),
            'placeholder' => __('Select a territory')
        ],
            $this->wantsJSON() ? null : api()->territoryList(),
            function ($values) {
                $values = json_decode($values);
                $where = join(' OR ', array_fill(0, count($values), 'territory_id LIKE ?'));
                $values = array_map(function ($field) {return $field . '%';}, $values);

                $this->crud->query->whereRaw($where, $values);
            });

        $this->crud->addFilter([
            'name' => 'process',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('process')),
            'placeholder' => __('Select a process')
        ],
            url('admin/process/ajax/filter/'),
            function ($value) {
                $this->crud->addClause('where', 'process_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'user',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('volunteer')),
            'placeholder' => __('Select a volunteer')
        ],
            url('admin/user/ajax/filter/' . User::VOLUNTEER),
            function ($value) {
                $this->crud->addClause('where', 'user_id', $value);
            });

        $this->crud->query->with(['process', 'territory', 'user']);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in ProtocolRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Add user to the partner
        $request->merge(['user_id' => backpack_user()->id]);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
