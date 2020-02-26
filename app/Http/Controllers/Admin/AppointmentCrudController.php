<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\AppointmentRequest as StoreRequest;
use App\Http\Requests\AppointmentRequest as UpdateRequest;
use App\Models\Appointment;
use App\User;
use Carbon\Carbon;
use DB;

/**
 * Class AppointmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AppointmentCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Appointment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/appointment');
        $this->crud->setEntityNameStrings(__('appointment'), __('appointments'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['process_id', 'vet_id_1', 'date_1', 'vet_id_2', 'date_2', 'amount_males', 'amount_females', 'amount_other', 'notes_deliver', 'notes_collect', 'notes_contact', 'notes_godfather', 'notes_info', 'notes', 'status']);

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
            'label' => ucfirst(__('vet')) . ' 1',
            'name' => 'vet_id_1',
            'type' => 'select2_from_ajax',
            'entity' => 'vet1',
            'attribute' => 'name',
            'model' => '\App\Models\Vet',
            'data_source' => url('admin/vet/ajax/search'),
            'placeholder' => __('Select a vet'),
            'minimum_input_length' => 2,
            'default' => \Request::get('vet') ?: false,
        ]);

        $this->crud->addField([
            'label' => __('Date') . ' 1',
            'name' => 'date_1',
            'type' => 'date',
            'default' => Carbon::today()->toDateString(),
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('vet')) . ' 2',
            'name' => 'vet_id_2',
            'type' => 'select2_from_ajax',
            'entity' => 'vet2',
            'attribute' => 'name',
            'model' => '\App\Models\Vet',
            'data_source' => url('admin/vet/ajax/search'),
            'placeholder' => __('Select a vet'),
            'minimum_input_length' => 2,
            'default' => \Request::get('vet') ?: false,
        ]);

        $this->crud->addField([
            'label' => __('Date') . ' 2',
            'name' => 'date_2',
            'type' => 'date',
        ]);

        $this->crud->addField([
            'label' => __('Males Amount'),
            'type' => 'number',
            'name' => 'amount_males',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 200],
        ]);

        $this->crud->addField([
            'label' => __('Females Amount'),
            'type' => 'number',
            'name' => 'amount_females',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 200],
        ]);

        $this->crud->addField([
            'label' => __('Undefined Amount'),
            'type' => 'number',
            'name' => 'amount_other',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 200],
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('treatments')),
            'name' => 'treatments',
            'type' => 'relation_table',
            'route' => '/admin/treatment',
            'buttons' => is('admin', 'accountancy') ? ['add'] : [],
            'columns' => [
                'id' => [
                    'label' => 'ID',
                    'name' => 'id',
                ],
                'name' => [
                    'label' => ucfirst(__('treatment type')),
                    'name' => 'treatmentTypeName',
                ],
                'affected_animals' => [
                    'label' => __('Affected Animals'),
                    'name' => 'affected_animals',
                ],
                'affected_animals_new' => [
                    'label' => __('New affected Animals'),
                    'name' => 'affected_animals_new',
                ],
                'expense' => [
                    'label' => __('Expense'),
                    'name' => 'fullExpense',
                ],
                'date' => [
                    'label' => __('Date'),
                    'name' => 'date',
                ],
                'status' => [
                    'label' => __('Status'),
                    'name' => 'fullStatus',
                ],
            ],
        ]);

        $this->separator(__('Infos'))->afterField('amount_other');

        $this->crud->addField([
            'label' => 'Quem entrega o animal na clínica',
            'type' => 'textarea',
            'name' => 'notes_deliver',
        ]);

        $this->crud->addField([
            'label' => 'Quem vai buscar o animal ao final do dia',
            'type' => 'textarea',
            'name' => 'notes_collect',
        ]);

        $this->crud->addField([
            'label' => 'O teu contacto telefónico<br /><i style="font-size:14px;">Como voluntário responsável por esses animais, é crucial que as clínicas tenham o teu contacto, para saber com quem falar caso haja algum contratempo.</i>',
            'type' => 'textarea',
            'name' => 'notes_contact',
        ]);

        $this->crud->addField([
            'label' => 'Quem apadrinha o animal',
            'type' => 'textarea',
            'name' => 'notes_godfather',
        ]);

        $this->crud->addField([
            'label' => 'Informações relevantes<br /><i style="font-size:14px;">Se há gatas gestantes, se há animais doentes, se há crias, o tamanho do corte, o peso no caso das cadelas/cães, etc.<br />No caso de consulta, indica sempre o motivo, o intervalo de horas para a mesma e um dia alternativo.</i>',
            'type' => 'textarea',
            'name' => 'notes_info',
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'type' => 'textarea',
            'name' => 'notes',
        ]);

        $this->separator()->afterField('notes');

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
            'attributes' => is('admin', 'appointments') ? [] : [
                'disabled' => 'disabled',
            ],
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['id', 'process_id', 'vet_id_1', 'date_1', 'vet_id_2', 'date_2', 'treatment_count', 'animal_count', 'status', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('process_id', [
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->selectRaw('appointments.*')
                    ->leftJoin('processes', 'processes.id', '=', 'appointments.process_id')
                    ->orderBy('processes.name', $direction);
            },
        ]);

        $this->crud->setColumnDetails('user_id', [
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->setColumnDetails('vet_id_1', [
            'label' => ucfirst(__('vet')) . ' 1',
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getVet1LinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->selectRaw('appointments.*')
                    ->leftJoin('vets', 'vets.id', '=', 'appointments.vet_id_1')
                    ->orderBy('vets.name', $direction);
            },
        ]);

        $this->crud->setColumnDetails('date_1', [
            'type' => 'date',
            'label' => __('Date') . ' 1',
        ]);

        $this->crud->setColumnDetails('vet_id_2', [
            'label' => ucfirst(__('vet')) . ' 2',
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getVet2LinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->selectRaw('appointments.*')
                    ->leftJoin('vets', 'vets.id', '=', 'appointments.vet_id_2')
                    ->orderBy('vets.name', $direction);
            },
        ]);

        $this->crud->setColumnDetails('date_2', [
            'type' => 'date',
            'label' => __('Date') . ' 2',
        ]);

        $this->crud->setColumnDetails('animal_count', [
            'name' => 'animal_count',
            'label' => __('Animals') . ' M/F | ?',
            'type' => 'model_function',
            'function_name' => 'getAnimalsValue',
        ]);

        $this->crud->setColumnDetails('treatment_count', [
            'name' => 'treatment_count',
            'label' => ucfirst(__('treatments')),
            'type' => 'model_function',
            'function_name' => 'getTreatmentsCountValue',
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'model_function',
            'function_name' => 'getStatusWithClassAttribute',
            'label' => __('Status'),
        ]);

        $this->crud->addColumn([
            'name' => 'notes_deliver',
            'label' => __('Notes Deliver'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'notes_collect',
            'label' => __('Notes Collect'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'notes_contact',
            'label' => __('Notes Contact'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'notes_godfather',
            'label' => __('Notes Godfather'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'notes_info',
            'label' => __('Notes Info'),
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'notes',
            'label' => __('Notes'),
            'visibleInTable' => false,
        ]);

        // ------ CRUD DETAILS ROW
        $this->crud->enableExportButtons();

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

        if (is('admin', 'appointments')) {
            $this->crud->addFilter([
                'name' => 'user',
                'type' => 'select2_ajax',
                'label' => ucfirst(__('volunteer')),
                'placeholder' => __('Select a volunteer'),
            ],
                url('admin/user/ajax/filter/' . User::ROLE_VOLUNTEER),
                function ($value) {
                    $this->crud->addClause('where', 'user_id', $value);
                });
        }

        $this->crud->addFilter([
            'name' => 'vet',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('vet')),
            'placeholder' => __('Select a vet'),
        ],
            url('admin/vet/ajax/filter'),
            function ($value) {
                $this->crud->query->whereRaw('(vet_id_1 = ? OR vet_id_2 = ?)', [$value, $value]);
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
                $this->crud->query->whereRaw('((date_1 >= ? AND date_1 <= DATE_ADD(?, INTERVAL 1 DAY)) OR (date_2 >= ? AND date_2 <= DATE_ADD(?, INTERVAL 1 DAY)))',
                    [$dates->from, $dates->to, $dates->from, $dates->to]);
            });

        $this->crud->addFilter([
            'name' => 'value',
            'type' => 'range',
            'label' => __('Animal count'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);
                if (is_numeric($range->from)) {
                    $this->crud->addClause('where', DB::raw('amount_males + amount_females + amount_other'), '>=', $range->from);
                }

                if (is_numeric($range->to)) {
                    $this->crud->addClause('where', DB::raw('amount_males + amount_females + amount_other'), '<=', $range->to);
                }

            });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            [
                'approving' => __('approving'),
                'approved' => __('approved'),
            ],
            function ($values) {
                $values = json_decode($values);
                if (in_array('approved', $values)) {
                    $values = array_merge($values, ['approved_option_1', 'approved_option_2']);
                }
                $this->crud->addClause('whereIn', 'status', $values);
            });

        // Buttons
        $this->crud->addButtonFromModelFunction('line', 'add_treatment', 'addTreatment', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'approve_appointment', 'approveAppointment', 'beginning');

        // ------ CRUD ACCESS
        if (!is(['admin', 'volunteer'])) {
            $this->crud->denyAccess(['list', 'create', 'delete']);
        }

        if (!is('admin', ['appointments', 'accountancy'])) {
            $this->crud->denyAccess(['update']);

            $this->crud->addClause('where', 'user_id', backpack_user()->id);
        }

        if (!is('admin', 'appointments')) {
            $this->crud->removeButton('approve_appointment', 'line');
        }

        // ------ ADVANCED QUERIES
        if (!is('admin')) {
            $this->crud->addClause('whereHas', 'process', function ($query) {
                $headquarters = restrictToHeadquarters();
                $query->whereIn('headquarter_id', $headquarters ?: []);
            })->get();
        } else {
            // Headquarter filter
            $headquarter_id = admin() ? \Session::get('headquarter', null) : backpack_user()->headquarter_id;
            if ($headquarter_id) {
                $this->crud->query->whereHas('process', function ($query) use ($headquarter_id) {
                    $query->where('headquarter_id', $headquarter_id);
                })->get();
            }
        }

        $this->crud->query->with(['process', 'vet1', 'vet2', 'user']);

        $this->crud->addClause('with', ['treatments' => function ($query) {
            $query->selectRaw('appointment_id, count(*) as treatments_count')
                ->groupBy('appointment_id');
        }]);

        $this->crud->addClause('orderBy', 'appointments.id', 'DESC');

        // Extra filter
        // Must be here because of a previous declaration of ->with(treatment)
        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'treatments',
            'label' => __('No treatments'),
        ],
            false,
            function ($values) {
                if ($values) {
                    // This selects appointments with treatments to use on a whereNotIn
                    $withTreatments = Appointment::select('appointments.id')
                        ->join('treatments', 'treatments.appointment_id', 'appointments.id')
                        ->groupBy('appointments.id')
                        ->pluck('id');

                    $this->crud->addClause('whereNotIn', 'appointments.id', $withTreatments);
                }
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'archive',
            'label' => __('Archive'),
        ], false, function ($value) {});

        foreach ($this->crud->filters as $filter) {
            if ($filter->name == 'archive' && $filter->currentValue == null) {

                // Treatments created more than 1 Day ago
                $this->crud->addClause('whereDoesntHave', 'treatments', function ($query) {
                    $query->whereRaw('created_at < DATE_SUB(curdate(), INTERVAL 1 DAY)');
                })->get();
            }
        }

        // add asterisk for fields that are required in AppointmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function destroy($id)
    {
        $appointment = Appointment::with('treatments')->find($id);

        // Avoid destroy if there are treatments
        if ($appointment && count($appointment->treatments) > 0) {
            return false;
        }

        // Common volunteers can only destroy their own appointments
        // if (!is('admin', ['appointments', 'accountancy'])) {
        //     if ($appointment->user_id != backpack_user()->id) {
        //         return false;
        //     }
        // }

        return parent::destroy($id);
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
