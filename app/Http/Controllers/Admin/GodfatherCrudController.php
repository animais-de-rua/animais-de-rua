<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\GodfatherRequest as StoreRequest;
use App\Http\Requests\GodfatherRequest as UpdateRequest;
use App\Models\Godfather;
use App\User;

/**
 * Class GodfatherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GodfatherCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Godfather');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/godfather');
        $this->crud->setEntityNameStrings(__('godfather'), __('godfathers'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'label' => __('Email'),
            'name' => 'email',
            'type' => 'email',
        ]);
        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
            'type' => 'text',
            'attributes' => [
                'type' => 'tel',
                'maxlength' => '14',
            ],
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territory')),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => api()->territoryList(),
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
                'default' => count($headquarters) ? $headquarters[0] : null,
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
            ]);
        }

        $this->separator();

        $this->crud->addField([
            'label' => ucfirst(__('donations')),
            'name' => 'donations',
            'type' => 'relation_table',
            'route' => '/admin/donation',
            'buttons' => is('admin', 'accountancy') ? ['add'] : [],
            'columns' => [
                'name' => [
                    'label' => ucfirst(__('process')),
                    'name' => 'processLink',
                ],
                'value' => [
                    'label' => __('Value'),
                    'name' => 'fullValue',
                ],
                'date' => [
                    'label' => __('Date'),
                    'name' => 'date',
                ],
            ],
        ]);

        $this->crud->addField([
            'label' => __('Stats'),
            'name' => 'stats',
            'type' => 'stats',
            'rows' => [
                'donated' => [
                    'label' => __('Total Donated'),
                    'value' => 'getTotalDonatedStats',
                ],
                'donations' => [
                    'label' => __('Total Donations'),
                    'value' => 'getTotalDonationsStats',
                ],
            ],
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['id', 'name', 'email', 'phone', 'donations', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('email', [
            'label' => __('Email'),
            'type' => 'email',
        ]);

        $this->crud->setColumnDetails('phone', [
            'label' => __('Phone'),
        ]);

        $this->crud->setColumnDetails('donations', [
            'name' => 'donations',
            'label' => __('Total Donated'),
            'type' => 'model_function',
            'function_name' => 'getTotalDonatedValue',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
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

        // Filtrers
        if (is('admin')) {
            $this->crud->addFilter([
                'name' => 'user',
                'type' => 'select2_ajax',
                'label' => ucfirst(__('volunteer')),
                'placeholder' => __('Select a volunteer'),
            ],
                url('admin/user/ajax/filter/' . User::VOLUNTEER),
                function ($value) {
                    $this->crud->addClause('where', 'user_id', $value);
                });

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
        if (!is('admin', 'accountancy')) {
            $this->crud->denyAccess(['list', 'create']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete', 'update']);

            $this->crud->addClause('whereIn', 'headquarter_id', restrictToHeadquarters());
        }

        // ------ ADVANCED QUERIES
        $this->crud->addClause('orderBy', 'id', 'DESC');

        $this->crud->addClause('with', ['donations' => function ($query) {
            $query->selectRaw('godfather_id, sum(value) as total')
                ->groupBy(['godfather_id']);
        }]);

        // Add asterisk for fields that are required
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Add user
        $headquarters = restrictToHeadquarters();
        $request->merge([
            'user_id' => backpack_user()->id,
            'headquarter_id' => count($headquarters) ? $headquarters[0] : null,
        ]);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
