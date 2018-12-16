<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdopterRequest as UpdateRequest;
use App\Http\Requests\AdopterStoreRequest as StoreRequest;
use App\User;
use Carbon\Carbon;

/**
 * Class AdopterCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdopterCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Adopter');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/adopter');
        $this->crud->setEntityNameStrings(__('adopter'), __('adopters'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['adoption_id', 'territory_id', 'name', 'email', 'phone', 'address', 'zip_code', 'id_card', 'adoption_date']);

        $this->crud->addField([
            'label' => ucfirst(__('adoption')),
            'name' => 'adoption_id',
            'type' => 'select2_from_ajax',
            'entity' => 'adoption',
            'attribute' => 'detail',
            'model' => '\App\Models\Adoption',
            'data_source' => url('admin/adoption/ajax/search'),
            'placeholder' => __('Select a adoption'),
            'minimum_input_length' => 2,
            'default' => \Request::get('adoption') ?: false,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territory')),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => api()->territoryList(),
            'allows_null' => true,
        ]);

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
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Postal Code'),
            'name' => 'zip_code',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('ID Card'),
            'name' => 'id_card',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Adoption Date'),
            'name' => 'adoption_date',
            'type' => 'date',
            'default' => Carbon::today()->toDateString(),
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

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['id', 'adoption_id', 'territory_id', 'name', 'email', 'phone', 'adoption_date', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('adoption_id', [
            'name' => 'adoption',
            'label' => ucfirst(__('adoption')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getAdoptionLinkAttribute',
        ]);

        $this->crud->setColumnDetails('territory_id', [
            'label' => ucfirst(__('territory')),
            'type' => 'select',
            'entity' => 'territory',
            'attribute' => 'name',
            'model' => "App\Models\Territory",
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('email', [
            'label' => __('Email'),
        ]);

        $this->crud->setColumnDetails('phone', [
            'label' => __('Phone'),
        ]);

        $this->crud->setColumnDetails('adoption_date', [
            'label' => __('Adoption Date'),
            'type' => 'date',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        // Filters
        $this->crud->addFilter([
            'name' => 'territory_id',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('territory')),
            'placeholder' => __('Select a territory'),
        ],
            $this->wantsJSON() ? null : api()->territoryList(),
            function ($values) {
                $values = json_decode($values);
                $where = join(' OR ', array_fill(0, count($values), 'territory_id LIKE ?'));
                $values = array_map(function ($field) {return $field . '%';}, $values);

                $this->crud->query->whereRaw($where, $values);
            });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'from_to',
            'label' => __('Date range'),
            'format' => 'DD/MM/YYYY',
            'firstDay' => 1,
        ],
            false,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->query->whereRaw('adoption_date >= ? AND adoption_date <= DATE_ADD(?, INTERVAL 1 DAY)', [$dates->from, $dates->to]);
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
        $this->crud->query->with(['adoption']);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        // add asterisk for fields that are required in AdopterRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Add user
        $request->merge(['user_id' => backpack_user()->id]);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
