<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\ProtocolRequest as StoreRequest;
use App\Http\Requests\ProtocolRequest as UpdateRequest;

/**
 * Class ProtocolCrudController
 *
 * @property-read CrudPanel $crud
 */
class ProtocolCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Protocol');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/protocol');
        $this->crud->setEntityNameStrings(__('protocol'), __('protocols'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->setColumns(['id', 'name', 'territory', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
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

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user_id',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        if (is('admin')) {
            $this->crud->addColumn([
                'label' => ucfirst(__('headquarter')),
                'type' => 'select',
                'entity' => 'headquarter',
                'attribute' => 'name',
                'model' => "App\Models\Headquarter",
            ]);
        }

        $this->crud->addFields(['name', 'territory_id']);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territory')),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => $this->wantsJSON() ? null : api()->actingTerritoryList(),
            'allows_null' => true,
        ]);

        if (is('admin')) {
            $headquarters = restrictToHeadquarters();
            $this->crud->addField([
                'label' => ucfirst(__('headquarter')),
                'name' => 'headquarter_id',
                'type' => 'select2',
                'entity' => 'headquarter',
                'attribute' => 'name',
                'model' => 'App\Models\Headquarter',
                'default' => $headquarters && count($headquarters) ? $headquarters[0] : null,
            ]);

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

        if (is('admin')) {
            $this->crud->addFilter([
                'name' => 'headquarter_id',
                'type' => 'select2_multiple',
                'label' => ucfirst(__('headquarter')),
                'placeholder' => __('Select a headquarter'),
            ],
                $this->wantsJSON() ? null : api()->headquarterList(),
                function ($values) {
                    $this->crud->addClause('whereIn', 'headquarter_id', json_decode($values));
                });
        }

        // ------ CRUD ACCESS
        if (! is('admin', 'protocols')) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (! is('admin')) {
            $this->crud->denyAccess(['delete']);

            $headquarters = restrictToHeadquarters();
            $this->crud->addClause('whereIn', 'headquarter_id', $headquarters ?: []);
            $this->crud->removeColumn('headquarter');
        }

        // ------ ADVANCED QUERIES
        $this->crud->query->with(['territory', 'user']);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in ProtocolRequest
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
