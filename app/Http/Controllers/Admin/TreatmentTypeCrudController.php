<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\TreatmentTypeRequest as StoreRequest;
use App\Http\Requests\TreatmentTypeRequest as UpdateRequest;

/**
 * Class TreatmentTypeCrudController
 *
 * @property-read CrudPanel $crud
 */
class TreatmentTypeCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TreatmentType');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/treatmenttype');
        $this->crud->setEntityNameStrings(__('treatment type'), __('treatment types'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->setColumns(['name', 'operation_time', 'total_expenses', 'total_operations', 'average_expense']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('operation_time', [
            'label' => __('Operation Time'),
        ]);

        // ------ ADVANCED QUERIES
        $this->crud->query->selectRaw('treatment_type_id, sum(expense) as total_expenses, count(*) as total_operations, sum(expense) / count(*) as average')
            ->leftJoin('treatments', 'treatment_types.id', '=', 'treatments.treatment_type_id')
            ->groupBy('treatment_type_id');

        $this->crud->setColumnDetails('total_expenses', [
            'name' => 'total_expenses',
            'label' => __('Total Expenses'),
            'type' => 'model_function',
            'function_name' => 'getTotalExpensesValue',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->orderBy('total_expenses', $direction);
            },
        ]);

        $this->crud->setColumnDetails('total_operations', [
            'name' => 'total_operations',
            'label' => __('Total Operations'),
            'type' => 'model_function',
            'function_name' => 'getTotalOperationsValue',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->orderBy('total_operations', $direction);
            },
        ]);

        $this->crud->setColumnDetails('average_expense', [
            'name' => 'average_expense',
            'label' => __('Average Expense'),
            'type' => 'model_function',
            'function_name' => 'getOperationsAverageValue',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->orderBy('average', $direction);
            },
        ]);

        // ------ CRUD FIELDS
        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Operation Time').' (hh:mm)',
            'name' => 'operation_time',
            'type' => 'time',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 3 * 24 * 60],
        ]);

        // Filter
        $this->crud->addFilter([
            'name' => 'expenses',
            'type' => 'range',
            'label' => __('Total Expenses'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query->whereHas('treatments', function ($query) use ($range) {
                    $query->selectRaw('treatment_type_id, sum(expense) as total_expenses')
                        ->groupBy(['treatment_type_id']);

                    if (is_numeric($range->from)) {
                        $query->having('total_expenses', '>=', $range->from);
                    }

                    if (is_numeric($range->to)) {
                        $query->having('total_expenses', '<=', $range->to);
                    }

                });
            });

        $this->crud->addFilter([
            'name' => 'operations',
            'type' => 'range',
            'label' => __('Total Operations'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query->whereHas('treatments', function ($query) use ($range) {
                    $query->selectRaw('treatment_type_id, count(*) as total_operations')
                        ->groupBy(['treatment_type_id']);

                    if (is_numeric($range->from)) {
                        $query->having('total_operations', '>=', $range->from);
                    }

                    if (is_numeric($range->to)) {
                        $query->having('total_operations', '<=', $range->to);
                    }

                });
            });

        // ------ CRUD ACCESS
        if (! is('admin')) {
            $this->crud->denyAccess(['list', 'create', 'update', 'delete']);
        }

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in TreatmentTypeRequest
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
