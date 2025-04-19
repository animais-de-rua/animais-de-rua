<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\FatRequest as StoreRequest;
use App\Http\Requests\FatRequest as UpdateRequest;
use App\Models\Fat;
use App\User;

/**
 * Class FatCrudController
 *
 * @property-read CrudPanel $crud
 */
class FatCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Fat');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/fat');
        $this->crud->setEntityNameStrings('FAT', 'FAT');

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
            $this->crud->addField([
                'label' => ucfirst(__('headquarter')),
                'type' => 'select2_multiple_data_source',
                'name' => 'headquarters',
                'attribute' => 'name',
                'model' => api()->headquarterSearch(),
                'pivot' => true,
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

        $this->crud->addField([
            'label' => __('Notes'),
            'name' => 'notes',
            'type' => 'textarea',
        ]);

        $this->separator();

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['id', 'name', 'email', 'phone', 'user_id']);

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

        $this->crud->setColumnDetails('user_id', [
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->selectRaw('fats.*')
                    ->leftJoin('users', 'users.id', '=', 'fats.user_id')
                    ->orderBy('users.name', $direction);
            },
        ]);

        if (is('admin')) {
            $this->crud->addColumn([
                'label' => ucfirst(__('headquarter')),
                'type' => 'select',
                'entity' => 'headquarters',
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
                url('admin/user/ajax/filter/'.User::ROLE_VOLUNTEER),
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
                    $this->crud->addClause('whereHas', 'headquarters', function ($query) use ($values) {
                        $query->whereIn('headquarter_id', json_decode($values) ?: []);
                    })->get();
                });
        }

        // ------ CRUD ACCESS
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        if (! is('admin', 'adoptions')) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (! is('admin')) {
            $this->crud->denyAccess(['delete']);

            $this->crud->addClause('whereHas', 'headquarters', function ($query) {
                $headquarters = restrictToHeadquarters();
                $query->whereIn('headquarter_id', $headquarters ?: []);
            });
        }

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['user']);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        // Add asterisk for fields that are required
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function showDetailsRow($id)
    {
        $fat = Fat::select(['notes'])->find($id);

        return "<div style='margin:5px 8px'>
                <p style='white-space: pre-wrap;'><i>".__('Notes')."</i>: $fat->notes</p>
            </div>";
    }

    public function store(StoreRequest $request)
    {
        // Add user
        $headquarters = restrictToHeadquarters();
        $request->merge([
            'user_id' => backpack_user()->id,
            'headquarter_id' => $headquarters && count($headquarters) ? $headquarters[0] : null,
        ]);

        // Add user
        $request->merge([
            'user_id' => backpack_user()->id,
        ]);

        $store = parent::storeCrud($request);

        // Add headquarters
        $headquarters = restrictToHeadquarters();
        if (! $request->headquarters && $headquarters) {
            $fat = $this->crud->entry;
            $fat->headquarters()->attach($headquarters);
        }

        return $store;
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
