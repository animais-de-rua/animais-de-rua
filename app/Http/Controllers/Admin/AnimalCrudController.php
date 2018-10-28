<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\AnimalRequest as StoreRequest;
use App\Http\Requests\AnimalRequest as UpdateRequest;
use App\Models\Animal;

/**
 * Class AnimalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AnimalCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Animal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/animal');
        $this->crud->setEntityNameStrings(__('animal'), __('animals'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['name', 'adoption_id', 'age', 'gender', 'sterilized', 'vaccinated']);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('adoption')),
            'name' => 'adoption_id',
            'type' => 'select2_from_ajax',
            'entity' => 'adoption',
            'attribute' => 'name',
            'model' => '\App\Models\Adoption',
            'data_source' => url('admin/adoption/ajax/search'),
            'placeholder' => __('Select an adoption process'),
            'minimum_input_length' => 2,
            'default' => \Request::has('adoption') ?? false,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('age')),
            'name' => 'age',
            'type' => 'age',
            'default' => [0, 0],
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('gender')),
            'name' => 'gender',
            'type' => 'enum',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('sterilized')),
            'name' => 'sterilized',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('vaccinated')),
            'name' => 'vaccinated',
            'type' => 'checkbox',
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'adoption_id', 'age', 'gender', 'sterilized', 'vaccinated']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('adoption_id', [
            'name' => 'adoption',
            'label' => ucfirst(__('adoption process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getAdoptionLinkAttribute',
        ]);

        $this->crud->setColumnDetails('age', [
            'name' => 'age',
            'label' => ucfirst(__('age')),
            'type' => 'model_function',
            'function_name' => 'getAgeValueAttribute',
        ]);

        $this->crud->setColumnDetails('gender', [
            'type' => 'trans',
            'label' => ucfirst(__('gender')),
        ]);

        $this->crud->setColumnDetails('sterilized', [
            'type' => 'boolean',
            'label' => ucfirst(__('sterilized')),
        ]);

        $this->crud->setColumnDetails('vaccinated', [
            'type' => 'boolean',
            'label' => ucfirst(__('vaccinated')),
        ]);

        // Filtrers
        $this->crud->addFilter([
            'name' => 'adoption',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('adoption process')),
            'placeholder' => __('Select an adoption process'),
        ],
            url('admin/adoption/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'adoption_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'gender',
            'type' => 'select2',
            'label' => ucfirst(__('gender')),
            'placeholder' => __('Select a gender'),
        ],
            EnumHelper::translate('animal.gender'),
            function ($value) {
                $this->crud->addClause('where', 'gender', $value);
            });

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'sterilized',
            'label' => ucfirst(__('sterilized')),
        ],
            EnumHelper::translate('general.boolean'),
            function ($value) {
                $this->crud->addClause('where', 'sterilized', $value);
            });

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'vaccinated',
            'label' => ucfirst(__('vaccinated')),
        ],
            EnumHelper::translate('general.boolean'),
            function ($value) {
                $this->crud->addClause('where', 'vaccinated', $value);
            });

        $this->crud->addFilter([
            'name' => 'number',
            'type' => 'range',
            'label' => sprintf('%s (%s)', ucfirst(__('age')), ucfirst(__('months'))),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'age', '>=', (float) $range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'age', '<=', (float) $range->to);
                }
            });

        // ------ ADVANCED QUERIES
        $this->crud->query->with(['adoption']);

        // add asterisk for fields that are required in AnimalRequest
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
