<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProtocolRequestRequest as StoreRequest;
use App\Http\Requests\ProtocolRequestRequest as UpdateRequest;
use App\User;

/**
 * Class ProtocolRequestCrudController
 *
 * @property-read CrudPanel $crud
 */
class ProtocolRequestCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ProtocolRequest');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/protocol-request');
        $this->crud->setEntityNameStrings(__('request'), __('requests'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->setColumns(['id', 'council', 'name', 'territory', 'protocol', 'process', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('council', [
            'label' => 'ID '.ucfirst(__('request')),
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('territory', [
            'label' => ucfirst(__('territory')),
            'type' => 'select',
            'name' => 'territory',
            'entity' => 'territory',
            'attribute' => 'name',
            'model' => "App\Models\Territory",
        ]);

        $this->crud->setColumnDetails('protocol', [
            'name' => 'protocol',
            'label' => ucfirst(__('protocol')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProtocolLinkAttribute',
        ]);

        $this->crud->setColumnDetails('process', [
            'name' => 'process',
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user_id',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'label' => __('E-Mail Address'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'phone',
            'label' => __('Phone'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'address',
            'label' => __('Address'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => __('Description'),
            'visibleInTable' => false,
        ]);

        $this->crud->addFields(['protocol_id', 'process_id', 'council', 'name', 'email', 'phone', 'address', 'territory_id', 'description']);

        $this->crud->addField([
            'label' => 'ID '.ucfirst(__('request')),
            'name' => 'council',
        ]);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Email'),
            'name' => 'email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'label' => __('Description'),
            'name' => 'description',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territory')),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => $this->wantsJSON() ? null : api()->territoryList(),
            'allows_null' => true,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('protocol')),
            'name' => 'protocol_id',
            'type' => 'select2_from_ajax',
            'entity' => 'protocol',
            'attribute' => 'name',
            'model' => '\App\Models\Protocol',
            'data_source' => url('admin/protocol/ajax/search'),
            'placeholder' => __('Select a protocol'),
            'minimum_input_length' => 2,
            'default' => \Request::get('protocol') ?: false,
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
            'default' => \Request::get('process') ?: false,
        ]);

        if (is('admin')) {
            $this->crud->addField([
                'label' => ucfirst(__('volunteer')),
                'name' => 'user_id',
                'type' => 'select2_from_ajax',
                'entity' => 'user',
                'attribute' => 'name',
                'model' => '\App\User',
                'placeholder' => '',
                'minimum_input_length' => 2,
                'data_source' => null,
                'attributes' => [
                    'disabled' => 'disabled',
                ],
            ], 'update');
        }

        // Filters
        $this->crud->addFilter([
            'name' => 'territory',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('territory')),
            'placeholder' => __('Select a territory'),
        ],
            $this->wantsJSON() ? null : api()->territoryList(),
            function ($values) {
                $values = json_decode($values);
                $where = implode(' OR ', array_fill(0, count($values), 'territory_id LIKE ?'));
                $values = array_map(function ($field) {
                    return $field.'%';
                }, $values);

                $this->crud->query->whereRaw($where, $values);
            });

        $this->crud->addFilter([
            'name' => 'protocol',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('protocol')),
            'placeholder' => __('Select a protocol'),
        ],
            url('admin/protocol/ajax/filter/'),
            function ($value) {
                $this->crud->addClause('where', 'protocol_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'process',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('process')),
            'placeholder' => __('Select a process'),
        ],
            url('admin/process/ajax/filter/'),
            function ($value) {
                $this->crud->addClause('where', 'process_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'user',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('volunteer')),
            'placeholder' => __('Select a volunteer'),
        ],
            url('admin/user/ajax/filter/'.User::ROLE_VOLUNTEER),
            function ($value) {
                $this->crud->addClause('where', 'user_id', $value);
            });

        // ------ CRUD ACCESS
        if (! is('admin', 'protocols')) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (! is('admin')) {
            $this->crud->denyAccess(['delete']);

            $this->crud->query->join('protocols', 'protocols_requests.protocol_id', '=', 'protocols.id');
            $this->crud->query->selectRaw('protocols_requests.*');

            $headquarters = restrictToHeadquarters();
            $this->crud->query->whereIn('protocols.headquarter_id', $headquarters ?: []);
        }

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['process', 'protocol', 'territory', 'user']);

        $this->crud->addClause('orderBy', 'protocols_requests.id', 'DESC');

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in ProtocolRequestRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Add user to the partner
        $request->merge(['user_id' => user()->id]);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
