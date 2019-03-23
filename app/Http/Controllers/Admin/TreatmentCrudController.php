<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\TreatmentRequest as StoreRequest;
use App\Http\Requests\TreatmentRequest as UpdateRequest;
use App\Models\Appointment;
use App\Models\Process;
use App\Models\Treatment;
use App\User;
use Carbon\Carbon;

/**
 * Class TreatmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TreatmentCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Treatment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/treatment');
        $this->crud->setEntityNameStrings(__('treatment'), __('treatments'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->setColumns(['id', 'appointment', 'treatment_type', 'vet', 'affected_animals', 'affected_animals_new', 'expense', 'date', 'status', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('appointment', [
            'name' => 'appointment',
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute',
        ]);

        $this->crud->setColumnDetails('treatment_type', [
            'label' => ucfirst(__('treatment type')),
            'type' => 'select',
            'name' => 'treatment_type_id',
            'entity' => 'treatment_type',
            'attribute' => 'name',
            'model' => "App\Models\TreatmentType",
        ]);

        $this->crud->setColumnDetails('vet', [
            'name' => 'vet',
            'label' => ucfirst(__('vet')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getVetLinkAttribute',
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'model_function',
            'function_name' => 'getStatusWithClassAttribute',
            'label' => __('Status'),
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->setColumnDetails('affected_animals', [
            'label' => __('Animals'),
            'type' => 'number',
        ]);

        $this->crud->setColumnDetails('affected_animals_new', [
            'label' => __('Animals') . ' (' . __('new') . ')',
            'type' => 'number',
        ]);

        $this->crud->setColumnDetails('expense', [
            'label' => __('Expense'),
            'suffix' => '€',
        ]);

        $this->crud->setColumnDetails('date', [
            'label' => __('Date'),
            'type' => 'date',
        ]);

        // ------ CRUD FIELDS
        $appointment_id = \Request::get('appointment') ?: false;
        $treatment_id = $this->getEntryID();
        $attributes = !$appointment_id && !$treatment_id ? ['disabled' => 'disabled'] : [];
        $vet_id = false;
        $date = false;

        if ($appointment_id) {
            $appointment = Appointment::find($appointment_id);
            $process = Process::find($appointment->process_id);

            $date = $appointment->getApprovedDate();
            $vet_id = $appointment->getApprovedVetID();
        }

        if ($treatment_id) {
            $treatment = Treatment::find($treatment_id);
            $appointment = Appointment::find($treatment->appointment_id);
            if ($appointment) {
                $process = Process::find($appointment->process_id);
            }
        }

        $max = 0;
        $total_animals = $total_affected_animals_new = 0;
        if (isset($process)) {
            $total_affected_animals_new = 0;
            $total_animals = $max = $process->amount;

            $total_animals = $process->amount;
            $total_affected_animals_new = $process->getTotalAffectedAnimalsNew();

            $max = max(0, $total_animals - $total_affected_animals_new);
        }

        if ($treatment_id) {
            $max += $treatment->affected_animals_new;
        }

        $this->crud->addField([
            'label' => ucfirst(__('appointment')),
            'name' => 'appointment_id',
            'type' => 'select2_from_ajax_reload',
            'entity' => 'appointment',
            'attribute' => 'detail',
            'model' => '\App\Models\Appointment',
            'data_source' => url('admin/appointment/ajax/search'),
            'placeholder' => __('Search for the date or the ID of the appointment'),
            'minimum_input_length' => 2,
            'default' => $appointment_id,
        ]);

        $this->separator();

        $this->crud->addField([
            'label' => ucfirst(__('vet')),
            'name' => 'vet_id',
            'type' => 'select2_from_ajax',
            'entity' => 'vet',
            'attribute' => 'name',
            'model' => '\App\Models\Vet',
            'data_source' => url('admin/vet/ajax/search'),
            'placeholder' => __('Select a vet'),
            'minimum_input_length' => 2,
            'default' => $vet_id,
            'readonly' => !!$vet_id,
            'attributes' => $attributes,
        ]);

        $this->crud->addField([
            'label' => __('Date'),
            'name' => 'date',
            'type' => 'date',
            'default' => $date ?: (isset($appointment) ? $appointment->date_1 : Carbon::today()->toDateString()),
            'attributes' => (($date || isset($appointment)) && !is('admin')) ? ['readonly' => 'readonly'] : $attributes,
        ]);

        $this->separator();

        $this->crud->addField([
            'label' => ucfirst(__('treatment type')),
            'type' => 'select2',
            'name' => 'treatment_type_id',
            'entity' => 'treatment_type',
            'attribute' => 'name',
            'model' => "App\Models\TreatmentType",
            'attributes' => $attributes,
        ]);

        // Animals
        $this->crud->addField([
            'label' => __('Affected Animals'),
            'name' => 'affected_animals',
            'type' => 'number',
            'default' => 1,
            'attributes' => array_merge($attributes, [
                'min' => 1,
                'max' => $total_animals ?? 0,
            ]),
        ]);

        $this->crud->addField([
            'label' => __('New affected Animals') . '<br />' .
            '<i>Assinalar apenas os animais que nunca tenham sido intervencionados.</i>' .
            (isset($process) || $treatment_id ? "<br /><i>O processo tem <b style='font-style:initial'>$total_animals</b> animais dos quais <b style='font-style:initial'>$total_affected_animals_new</b> já foram intervencionados.</i>" : ''),
            'name' => 'affected_animals_new',
            'type' => 'number',
            'default' => 0,
            'attributes' => array_merge($attributes, [
                'min' => 0,
                'max' => $max ?? 0,
            ]),
        ]);

        $this->crud->addField([
            'label' => __('Expense'),
            'name' => 'expense',
            'type' => 'number',
            'default' => 0,
            'attributes' => array_merge($attributes, [
                'min' => 0,
                'max' => 1000000,
                'step' => .01,
            ]),
            'prefix' => '€',
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
            'attributes' => is('admin', 'treatments') ? [] : [
                'disabled' => 'disabled',
            ],
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

        $this->crud->addField([
            'label' => __('Notes'),
            'name' => 'notes',
            'type' => 'textarea',
        ]);

        // Filter
        $this->crud->addFilter([
            'name' => 'process',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('process')),
            'placeholder' => __('Select a process'),
        ],
            url('admin/process/ajax/filter'),
            function ($value) {
                $this->crud->addClause('whereHas', 'appointment', function ($query) use ($value) {
                    $query->where('process_id', $value);
                })->get();
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
                    $this->crud->addClause('whereHas', 'appointment', function ($query) use ($values) {
                        $query->whereHas('process', function ($query) use ($values) {
                            $query->whereIn('headquarter_id', json_decode($values));
                        });
                    });
                });
        }

        $this->crud->addFilter([
            'name' => 'treatment_type_id',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('treatment type')),
            'placeholder' => __('Select a treatment type'),
        ],
            $this->wantsJSON() ? null : api()->treatmentTypeList(),
            function ($values) {
                $this->crud->addClause('whereIn', 'treatment_type_id', json_decode($values));
            });

        $this->crud->addFilter([
            'name' => 'vet',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('vet')),
            'placeholder' => __('Select a vet'),
        ],
            url('admin/vet/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'vet_id', $value);
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

        $this->crud->addFilter([
            'name' => 'expense',
            'type' => 'range',
            'label' => __('Expense') . ' €',
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                if (is_numeric($range->from)) {
                    $this->crud->addClause('where', 'expense', '>=', $range->from);
                }

                if (is_numeric($range->to)) {
                    $this->crud->addClause('where', 'expense', '<=', $range->from);
                }

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
                $this->crud->query->whereRaw('date >= ? AND date <= DATE_ADD(?, INTERVAL 1 DAY)', [$dates->from, $dates->to]);
            });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            EnumHelper::translate('treatment.status'),
            function ($values) {
                $this->crud->addClause('whereIn', 'status', json_decode($values));
            });

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // Buttons
        $this->crud->removeButton('update');
        if ($this->crud->hasAccess('update')) {
            $this->crud->addButtonFromModelFunction('line', 'custom_update_button', 'customUpdateButton', 'beginning');
        }

        $this->crud->addButtonFromModelFunction('line', 'approve_treatment', 'approveTreatment', 'beginning');

        // ------ CRUD ACCESS
        if (!is(['admin', 'volunteer'])) {
            $this->crud->denyAccess(['list', 'create']);
        }

        if (!is('admin')) {
            if (!is('admin', 'treatments') || (is('volunteer', 'treatments') && $treatment_id && $treatment->status == 'approved')) {
                $this->crud->denyAccess(['update']);
            }
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete']);

            $this->crud->addClause('whereHas', 'appointment', function ($query) {
                $query->whereHas('process', function ($query) {
                    $headquarters = restrictToHeadquarters();
                    $query->whereIn('headquarter_id', $headquarters ?: []);
                });
            })->get();
        }

        if (!is('admin', 'treatments')) {
            $this->crud->removeButton('approve_appointment', 'line');
        }

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['appointment.process', 'vet', 'user', 'treatment_type']);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        // add asterisk for fields that are required in TreatmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function showDetailsRow($id)
    {
        $treatment = Treatment::select(['notes'])->find($id);

        return "<div style='margin:5px 8px'>
                <p><i>Notas</i>: $treatment->notes</p>
            </div>";
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

        // Force Date and Vet
        $appointment = Appointment::find(\Request::get('appointment_id'));
        $request->merge([
            'date' => $appointment->getApprovedDate(),
            'vet_id' => $appointment->getApprovedVetID(),
        ]);

        return $redirect;
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }

    public function sync()
    {
        \Cache::forget('treatments_affected_animals_new');
    }
}
