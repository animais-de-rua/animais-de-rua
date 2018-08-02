<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Helpers\EnumHelper;
use App\Http\Requests\DonationRequest as StoreRequest;
use App\Http\Requests\DonationRequest as UpdateRequest;

/**
 * Class DonationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DonationCrudController extends CrudController
{
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
        $this->crud->addFields(['godfather_id', 'process_id', 'value', 'status', 'date']);

        $this->crud->addField([
            'label' => __("Value"),
            'name' => 'value',
            'type' => 'number'
        ]);

        $this->crud->addField([
            'label' => ucfirst(__("process")),
            'name' => 'process_id',
            'type' => 'select2_from_ajax',
            'entity' => 'process',
            'attribute' => 'detail',
            'model' => '\App\Models\Process',
            'data_source' => url("admin/process/ajax/search"),
            'placeholder' => __("Select a process"),
            'minimum_input_length' => 2,
            'default' => \Request::has('process') ?? false,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__("godfather")),
            'name' => 'godfather_id',
            'type' => 'select2_from_ajax',
            'entity' => 'godfather',
            'attribute' => 'detail',
            'model' => '\App\Models\Godfather',
            'data_source' => url("admin/godfather/ajax/search"),
            'placeholder' => __("Select a godfather"),
            'minimum_input_length' => 2,
            'default' => \Request::has('godfather') ?? false,
        ]);

        $this->crud->addField([
            'label' => __("Status"),
            'name' => 'status',
            'type' => 'enum'
        ]);

        $this->crud->addField([
            'label' => __("Date"),
            'name' => 'date',
            'type' => 'date',
            'default' => Carbon::today()->toDateString()
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['godfather', 'process', 'value', 'status']);

        $this->crud->setColumnDetails('godfather', [
            'name' => 'godfather',
            'label' => ucfirst(__("godfather")),
            'type' => "model_function",
            'limit' => 120,
            'function_name' => 'getGodfatherLinkAttribute'
        ]);

        $this->crud->setColumnDetails('process', [
            'name' => 'process',
            'label' => ucfirst(__("process")),
            'type' => "model_function",
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute'
        ]);

        $this->crud->setColumnDetails('value', [
            'name' => 'value',
            'label' => __("Value"),
            'type' => "model_function",
            'function_name' => 'getFullValueAttribute'
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status')
        ]);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // Filtrers
        $this->crud->addFilter([
            'name' => 'godfather',
            'type' => 'select2_ajax',
            'label'=> ucfirst(__("godfather")),
            'placeholder' => __('Select a godfather')
        ],
        url('admin/godfather/ajax/filter'),
        function($value) {
            $this->crud->addClause('where', 'godfather_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'process',
            'type' => 'select2_ajax',
            'label'=> ucfirst(__("process")),
            'placeholder' => __('Select a process')
        ],
        url('admin/process/ajax/filter'),
        function($value) {
            $this->crud->addClause('where', 'process_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2',
            'label'=> __("Status"),
        ],
        EnumHelper::translate('donation.status'),
        function($value) {
            $this->crud->addClause('where', 'status', $value);
        });

        $this->crud->addFilter([
            'name' => 'value',
            'type' => 'range',
            'label'=> __('Value'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value')
        ],
        true,
        function($value) {
            $range = json_decode($value);
            if (is_numeric($range->from)) $this->crud->addClause('where', 'value', '>=', (float) $range->from);
            if (is_numeric($range->to)) $this->crud->addClause('where', 'value', '<=', (float) $range->to);
        });

        // Add asterisk for fields that are required
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
