<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
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
        $this->crud->addFields(['process_id', 'vet_id_1', 'date_1', 'vet_id_2', 'date_2', 'amount_males', 'amount_females', 'notes', 'status', 'user_id']);

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
            'default' => \Request::has('vet') ?? false,
        ]);

        $this->crud->addField([
            'label' => __('Date'),
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
            'default' => \Request::has('vet') ?? false,
        ]);

        $this->crud->addField([
            'label' => __('Date'),
            'name' => 'date_2',
            'type' => 'date',
        ]);

        $this->crud->addField([
            'label' => __('Males Amount'),
            'type' => 'number',
            'name' => 'amount_males',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 100],
        ]);

        $this->crud->addField([
            'label' => __('Females Amount'),
            'type' => 'number',
            'name' => 'amount_females',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 100],
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'type' => 'wysiwyg',
            'name' => 'notes',
            'default' => 'a)&nbsp;<br />b)&nbsp;<br />c)&nbsp;<br />d)&nbsp;<br />e)&nbsp;',
            'hint' => '<ul style="list-style:lower-alpha;padding-left:16px;"><li>Quem entrega o animal na clínica</li>
<li>Quem vai buscar o animal ao final do dia</li>
<li>O teu contacto telefónico<br /><i style="font-size:14px;">Como voluntário responsável por esses animais, é crucial que as clínicas tenham o teu contacto, para saber com quem falar caso haja algum contratempo;</i></li>
<li>Quem apadrinha o animal</li>
<li>Informações relevantes<br /><i style="font-size:14px;">Se há gatas gestantes, se há animais doentes, se há crias, etc.</i></li></ul>
<p>No caso de consulta, indica sempre o motivo, o intervalo de horas para a mesma e um dia alternativo.</p>',
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['process_id', 'vet_id_1', 'date_1', 'vet_id_2', 'date_2', 'animal_count', 'status', 'user_id']);

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

        $this->crud->setColumnDetails('vet_id_1', [
            'name' => 'vet1',
            'label' => ucfirst(__('vet')) . ' 1',
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getVet1LinkAttribute',
        ]);

        $this->crud->setColumnDetails('date_1', [
            'type' => 'date',
            'label' => __('Date'),
        ]);

        $this->crud->setColumnDetails('vet_id_2', [
            'name' => 'vet2',
            'label' => ucfirst(__('vet')) . ' 2',
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getVet2LinkAttribute',
        ]);

        $this->crud->setColumnDetails('date_2', [
            'type' => 'date',
            'label' => __('Date'),
        ]);

        $this->crud->setColumnDetails('animal_count', [
            'name' => 'animal_count',
            'label' => __('Animals') . ' M/F',
            'type' => 'model_function',
            'function_name' => 'getAnimalsValue',
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status'),
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
                    $this->crud->addClause('where', DB::raw('amount_males + amount_females'), '>=', $range->from);
                }

                if (is_numeric($range->to)) {
                    $this->crud->addClause('where', DB::raw('amount_males + amount_females'), '<=', $range->to);
                }

            });

        $status_options = EnumHelper::translate('appointment.status');
        if (!is('admin', 'appointment')) {
            unset($status_options['approving']);
        }

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            $status_options,
            function ($values) {
                $this->crud->addClause('whereIn', 'status', json_decode($values));
            });

        // ------ ADVANCED QUERIES
        $this->crud->query->with(['process', 'vet1', 'vet2', 'user']);

        if (!is('admin', 'appointment')) {
            $this->crud->addClause('where', 'status', '<>', 'approving');
            $this->crud->denyAccess(['update']);
        }

        // Headquarter filter
        $headquarter_id = admin() ? \Session::get('headquarter', null) : backpack_user()->headquarter_id;
        if ($headquarter_id) {
            $this->crud->query->whereHas('process', function ($query) use ($headquarter_id) {
                $query->where('headquarter_id', $headquarter_id);
            })->get();
        }

        // add asterisk for fields that are required in AppointmentRequest
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
