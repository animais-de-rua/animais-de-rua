<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\AdoptionRequest as StoreRequest;
use App\Http\Requests\AdoptionRequest as UpdateRequest;
use App\Models\Adoption;
use App\User;

/**
 * Class AdoptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdoptionCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Adoption');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/adoption');
        $this->crud->setEntityNameStrings(__('adoption'), __('adoptions'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['name', 'process_id', 'fat_id', 'history']);

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
            'default' => \Request::has('process') ?? false,
        ]);

        $this->crud->addField([
            'label' => __('FAT'),
            'name' => 'fat_id',
            'type' => 'select2_from_ajax',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => '\App\User',
            'data_source' => url('admin/user/ajax/search/' . User::FAT),
            'placeholder' => __('Select a fat'),
            'minimum_input_length' => 2,
            'default' => \Request::has('user') ?? false,
        ]);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('History'),
            'type' => 'wysiwyg',
            'name' => 'history',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('animals')),
            'name' => 'animal',
            'type' => 'relation_table',
            'route' => '/admin/animal',
            'columns' => [
                'name' => [
                    'label' => ucfirst(__('name')),
                    'name' => 'name',
                ],
                'age' => [
                    'label' => ucfirst(__('age')),
                    'name' => 'ageValue',
                ],
                'gender' => [
                    'label' => ucfirst(__('gender')),
                    'name' => 'genderValue',
                ],
                'sterilized' => [
                    'label' => ucfirst(__('sterilized')),
                    'name' => 'sterilizedValue',
                ],
                'vaccinated' => [
                    'label' => ucfirst(__('vaccinated')),
                    'name' => 'vaccinatedValue',
                ],
            ],
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'process_id', 'user_id', 'fat_id', 'animal_count']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('process_id', [
            'name' => 'process',
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->setColumnDetails('fat_id', [
            'name' => 'fat',
            'label' => __('FAT'),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getFatLinkAttribute',
        ]);

        $this->crud->setColumnDetails('animal_count', [
            'name' => 'animal_count',
            'label' => __('Animals'),
            'type' => 'model_function',
            'function_name' => 'getAnimalsAttribute',
        ]);

        // Filtrers
        $this->crud->addFilter([
            'name' => 'process',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('process')),
            'placeholder' => __('Select a process'),
        ],
            url('admin/process/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'process_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'fat',
            'type' => 'select2_ajax',
            'label' => __('FAT'),
            'placeholder' => __('Select a FAT'),
        ],
            url('admin/user/ajax/filter/' . User::FAT),
            function ($value) {
                $this->crud->addClause('where', 'user_id', $value);
            });

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

        // ------ ADVANCED QUERIES
        $this->crud->query->with(['process', 'user', 'fat', 'animal']);

        // add asterisk for fields that are required in AdoptionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Add user to the treatment
        $request->merge(['user_id' => backpack_user()->id]);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
