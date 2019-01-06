<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\DonationRequest as StoreRequest;
use App\Http\Requests\DonationRequest as UpdateRequest;
use App\User;
use Carbon\Carbon;

/**
 * Class DonationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DonationCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Donation');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/donation');
        $this->crud->setEntityNameStrings(__('donation'), __('donations'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['godfather_id', 'process_id', 'value', 'date']);

        $this->crud->addField([
            'label' => __('Value'),
            'name' => 'value',
            'type' => 'number',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 1000000, 'step' => .01],
            'prefix' => '€',
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

        $this->crud->addField([
            'label' => ucfirst(__('godfather')),
            'name' => 'godfather_id',
            'type' => 'select2_from_ajax',
            'entity' => 'godfather',
            'attribute' => 'detail',
            'model' => '\App\Models\Godfather',
            'data_source' => url('admin/godfather/ajax/search'),
            'placeholder' => __('Select a godfather'),
            'minimum_input_length' => 2,
            'default' => \Request::get('godfather') ?: false,
        ]);

        $this->crud->addField([
            'label' => __('Date'),
            'name' => 'date',
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
        $this->crud->addColumns(['id', 'godfather', 'process', 'value', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('godfather', [
            'name' => 'godfather',
            'label' => ucfirst(__('godfather')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getGodfatherLinkAttribute',
        ]);

        $this->crud->setColumnDetails('process', [
            'name' => 'process',
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute',
        ]);

        $this->crud->setColumnDetails('value', [
            'name' => 'value',
            'label' => __('Value'),
            'type' => 'model_function',
            'function_name' => 'getFullValueAttribute',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // Filtrers
        $this->crud->addFilter([
            'name' => 'godfather',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('godfather')),
            'placeholder' => __('Select a godfather'),
        ],
            url('admin/godfather/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'godfather_id', $value);
            });

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
            'name' => 'value',
            'type' => 'range',
            'label' => __('Value'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);
                if (is_numeric($range->from)) {
                    $this->crud->addClause('where', 'value', '>=', (float) $range->from);
                }

                if (is_numeric($range->to)) {
                    $this->crud->addClause('where', 'value', '<=', (float) $range->to);
                }
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

        // ------ CRUD ACCESS
        if (!is('admin', 'accountancy')) {
            $this->crud->denyAccess(['list', 'create']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete', 'update']);

            $this->crud->addClause('whereHas', 'process', function ($query) {
                $headquarters = restrictToHeadquarters();
                $query->whereIn('headquarter_id', $headquarters ?: []);
            })->get();
        }

        // ------ ADVANCED QUERIES
        $this->crud->query->with(['process', 'godfather']);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        // Add asterisk for fields that are required
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Add user
        $request->merge(['user_id' => backpack_user()->id]);

        $redirect = parent::storeCrud($request);

        if ($request->save_action == 'save_and_new') {
            $referer = $request->header('referer');
            $redirect = redirect($referer);
        }

        return $redirect;
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
