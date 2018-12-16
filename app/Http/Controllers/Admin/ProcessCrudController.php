<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\ProcessRequest as StoreRequest;
use App\Http\Requests\ProcessRequest as UpdateRequest;
use App\Models\Adoption;
use App\Models\Appointment;
use App\Models\Donation;
use App\Models\Process;
use App\Models\Treatment;
use App\User;
use DB;

class ProcessCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Process');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/process');
        $this->crud->setEntityNameStrings(__('process'), __('processes'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['name', 'contact', 'phone', 'email', 'latlong', 'territory_id', 'headquarter_id', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status', 'images', 'history', 'notes', 'donations', 'treatments', 'stats']);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Contact'),
            'name' => 'contact',
        ]);

        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
        ]);

        $this->crud->addField([
            'label' => __('Email'),
            'type' => 'email',
            'name' => 'email',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territory')),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => $this->wantsJSON() ? null : api()->territoryList(),
            'allows_null' => true,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('headquarter')),
            'name' => 'headquarter_id',
            'type' => 'select2',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => 'App\Models\Headquarter',
        ]);

        $this->crud->addField([
            'label' => __('Specie'),
            'type' => 'enum',
            'name' => 'specie',
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
            'label' => __('Others Amount'),
            'type' => 'number',
            'name' => 'amount_other',
            'default' => 0,
            'attributes' => ['min' => 0, 'max' => 100],
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
            'attributes' => is('admin', 'processes') ? [] : [
                'disabled' => 'disabled',
            ],
        ]);

        if (is('admin')) {
            $this->crud->addField([
                'label' => __('Urgent'),
                'type' => 'checkbox',
                'name' => 'urgent',
            ]);
        }

        $this->crud->addField([
            'label' => __('Location'),
            'type' => 'latlng',
            'name' => 'latlong',
            'map_style' => 'width:100%; height:320px;',
            'google_api_key' => env('GOOGLE_API_KEY'),
            'default_zoom' => '9',
        ]);

        $this->crud->addField([
            'label' => __('History'),
            'type' => 'wysiwyg',
            'name' => 'history',
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'type' => 'wysiwyg',
            'name' => 'notes',
        ]);

        $this->crud->addField([
            'name' => 'images',
            'label' => __('Images'),
            'type' => 'dropzone',
            'upload-url' => '/admin/dropzone/images/process',
            'thumb' => 340,
            'size' => 800,
            'quality' => 82,
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

        $this->separator();

        $this->crud->addField($this->tableDonations());
        $this->crud->addField($this->tableTreatments());
        $this->crud->addField($this->tableAdoptions());
        $this->crud->addField($this->tableStats());

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['id', 'name', 'headquarter', 'created_at', 'specie', 'animal_count', 'status', 'urgent', 'total_donations', 'total_expenses', 'balance', 'total_operations', 'user_id', 'territory_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('created_at', [
            'type' => 'date',
            'label' => __('Date'),
        ]);

        $this->crud->setColumnDetails('territory_id', [
            'label' => ucfirst(__('territory')),
            'type' => 'select',
            'entity' => 'territory',
            'attribute' => 'name',
            'model' => "App\Models\Territory",
        ]);

        $this->crud->setColumnDetails('headquarter', [
            'label' => ucfirst(__('headquarter')),
            'type' => 'select',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => "App\Models\Headquarter",
        ]);

        $this->crud->setColumnDetails('specie', [
            'type' => 'trans',
            'label' => __('Specie'),
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status'),
        ]);

        $this->crud->setColumnDetails('urgent', [
            'type' => 'check',
            'label' => __('Urgent'),
        ]);

        $this->crud->setColumnDetails('animal_count', [
            'name' => 'animal_count',
            'label' => __('Animals') . ' M/F | O',
            'type' => 'model_function',
            'function_name' => 'getAnimalsValue',
        ]);

        $this->crud->setColumnDetails('total_donations', [
            'name' => 'total_donations',
            'label' => __('Donated'),
            'type' => 'model_function',
            'function_name' => 'getTotalDonatedValue',
        ]);

        $this->crud->setColumnDetails('total_expenses', [
            'name' => 'total_expenses',
            'label' => __('Expenses'),
            'type' => 'model_function',
            'function_name' => 'getTotalExpensesValue',
        ]);

        $this->crud->setColumnDetails('balance', [
            'name' => 'balance',
            'label' => __('Balance'),
            'type' => 'model_function',
            'function_name' => 'getBalanceValue',
        ]);

        $this->crud->setColumnDetails('total_operations', [
            'name' => 'total_operations',
            'label' => __('Operations'),
            'type' => 'model_function',
            'function_name' => 'getTotalOperationsValue',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        // For search proposes only
        $this->crud->addColumn('notes', [
            'name' => 'notes',
            'type' => 'text',
            'label' => __('Notes'),
            'visibleInTable' => false,
            'visibleInModal' => false,
            'visibleInExport' => false,
            'visibleInShow' => false,
        ]);

        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();

        // Buttons
        $this->crud->addButtonFromModelFunction('line', 'add_appointment', 'addAppointment', 'beginning');

        // Filtrers
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

        if (is('admin')) {
            $this->crud->addFilter([
                'name' => 'headquarter_id',
                'type' => 'select2_multiple',
                'label' => ucfirst(__('headquarter')),
                'placeholder' => __('Select a headquarter'),
            ],
                $this->wantsJSON() ? null : api()->headquarterList(),
                function ($values) {
                    $this->crud->addClause('whereIn', 'headquarter_id', json_decode($values));
                });
        }

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
                $this->crud->query->whereRaw('created_at >= ? AND created_at <= DATE_ADD(?, INTERVAL 1 DAY)', [$dates->from, $dates->to]);
            });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            EnumHelper::translate('process.status'),
            function ($values) {
                $this->crud->addClause('whereIn', 'status', json_decode($values));
            });

        $this->crud->addFilter([
            'name' => 'specie',
            'type' => 'select2_multiple',
            'label' => __('Specie'),
            'placeholder' => __('Select a specie'),
        ],
            EnumHelper::translate('process.specie'),
            function ($values) {
                $this->crud->addClause('whereIn', 'specie', json_decode($values));
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
            'name' => 'total_donations',
            'type' => 'range',
            'label' => ucfirst(__('donations')) . ' €',
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query->whereHas('donations', function ($query) use ($range) {
                    $query->selectRaw('process_id, sum(value) as total_donations')
                        ->groupBy('process_id');

                    if (is_numeric($range->from)) {
                        $query->having('total_donations', '>=', $range->from);
                    }

                    if (is_numeric($range->to)) {
                        $query->having('total_donations', '<=', $range->to);
                    }

                });
            });

        $this->crud->addFilter([
            'name' => 'total_expenses',
            'type' => 'range',
            'label' => __('Total Expenses') . ' €',
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query->whereHas('treatments', function ($query) use ($range) {
                    $query->selectRaw('process_id, sum(expense) as total_expenses')
                        ->groupBy('process_id');

                    if (is_numeric($range->from)) {
                        $query->having('total_expenses', '>=', $range->from);
                    }

                    if (is_numeric($range->to)) {
                        $query->having('total_expenses', '<=', $range->to);
                    }

                });
            });

        $this->crud->addFilter([
            'name' => 'total_operations',
            'type' => 'range',
            'label' => __('Total Operations'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query->whereHas('treatments', function ($query) use ($range) {
                    $query->selectRaw('process_id, count(*) as total_operations')
                        ->groupBy('process_id');

                    if (is_numeric($range->from)) {
                        $query->having('total_operations', '>=', $range->from);
                    }

                    if (is_numeric($range->to)) {
                        $query->having('total_operations', '<=', $range->to);
                    }

                });
            });

        $this->crud->addFilter([
            'name' => 'balance',
            'type' => 'range',
            'label' => __('Balance') . ' €',
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                $this->crud->query
                    ->join('donations', 'processes.id', '=', 'donations.process_id')
                    ->join('appointments', 'processes.id', '=', 'appointments.process_id')
                    ->join('treatments', 'appointments.id', '=', 'treatments.appointment_id')
                    ->select('processes.*')
                    ->selectRaw('sum(donations.value) - sum(treatments.expense) as balance')
                    ->groupBy('processes.id');

                if (is_numeric($range->from)) {
                    $this->crud->query->having(DB::raw('sum(donations.value) - sum(treatments.expense)'), '>=', $range->from);
                }

                if (is_numeric($range->to)) {
                    $this->crud->query->having(DB::raw('sum(donations.value) - sum(treatments.expense)'), '<=', $range->to);
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

        // ------ ADVANCED QUERIES
        if (!is(['admin', 'volunteer'], 'processes')) {
            $this->crud->denyAccess(['list', 'show']);
        }

        if (!is('admin', 'processes')) {
            $this->crud->denyAccess(['update']);
        }

        if (!is('admin')) {
            $this->crud->addClause('where', 'headquarter_id', restrictToHeadquarter());

            $this->crud->denyAccess(['delete']);

            $this->crud->removeColumn('headquarter');
        }

        $this->crud->addClause('with', ['donations' => function ($query) {
            $query->selectRaw('process_id, sum(value) as total_donations')
                ->groupBy('process_id');
        }]);

        $this->crud->addClause('with', ['treatments' => function ($query) {
            $query->selectRaw('process_id, sum(expense) as total_expenses, sum(affected_animals) as total_operations')
                ->groupBy('process_id');
        }]);

        $this->crud->addClause('orderBy', 'processes.id', 'DESC');

        $this->crud->allowAccess('show');

        // Add asterisk for fields that are required
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('total_donations');
        $this->crud->removeColumn('total_expenses');
        $this->crud->removeColumn('balance');
        $this->crud->removeColumn('total_operations');

        $this->crud->setColumnDetails('contact', [
            'label' => __('Contact'),
        ]);
        $this->crud->setColumnDetails('phone', [
            'label' => __('Phone'),
        ]);
        $this->crud->setColumnDetails('address', [
            'label' => __('Address'),
        ]);
        $this->crud->setColumnDetails('headquarter_id', [
            'label' => ucfirst(__('headquarter')),
            'type' => 'select',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => "App\Models\Headquarter",
        ]);

        $this->crud->setColumnDetails('images', [
            'name' => 'images',
            'label' => __('Images'),
            'type' => 'upload_multiple_image',
        ]);

        $this->crud->removeColumn('amount_males');
        $this->crud->removeColumn('amount_females');
        $this->crud->removeColumn('amount_other');

        $this->separator();

        $donations = Donation::where('process_id', $id)->get();
        $appointments = Appointment::where('process_id', $id)->get();
        $treatments = Treatment::whereIn('appointment_id', $appointments->pluck('id'))->get();
        $adoptions = Adoption::where('process_id', $id)->get();

        $this->crud->addColumn(array_merge(
            $this->tableDonations(),
            ['value' => $donations]
        ));

        $this->crud->addColumn(array_merge(
            $this->tableTreatments(),
            ['value' => $treatments]
        ));

        $this->crud->addColumn(array_merge(
            $this->tableAdoptions(),
            ['value' => $adoptions]
        ));

        $this->crud->addColumn($this->tableStats());

        return $content;
    }

    public function showDetailsRow($id)
    {
        $process = Process::select(['history', 'notes', 'contact', 'phone', 'email'])->find($id);

        return "<div style='margin:5px 8px'>
                <p>$process->contact, <a href='tel:$process->phone'>$process->phone</a><br /><a href='mailto:$process->email'>$process->email</a></p>
                <p>$process->history</p>
                <p>$process->notes</p>
            </div>";
    }

    public function store(StoreRequest $request)
    {
        // Add user
        $request->merge(['user_id' => backpack_user()->id]);

        // if (!restrictTo('admin')) {
        //     dd($request);
        // }

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }

    // Table Helper
    private function tableDonations()
    {
        return [
            'label' => ucfirst(__('donations')),
            'name' => 'donations',
            'type' => 'relation_table',
            'route' => '/admin/donation',
            'buttons' => is('admin', 'accountancy') ? ['add'] : [],
            'columns' => [
                'id' => [
                    'label' => 'ID',
                    'name' => 'id',
                ],
                'name' => [
                    'label' => ucfirst(__('godfather')),
                    'name' => 'godfatherLink',
                ],
                'value' => [
                    'label' => __('Value'),
                    'name' => 'fullValue',
                ],
                'date' => [
                    'label' => __('Date'),
                    'name' => 'date',
                ],
            ],
        ];
    }

    private function tableTreatments()
    {
        return [
            'label' => ucfirst(__('treatments')),
            'name' => 'treatments',
            'type' => 'relation_table',
            'route' => '/admin/treatment',
            'buttons' => is('admin', 'treatments') ? ['add'] : [],
            'columns' => [
                'id' => [
                    'label' => 'ID',
                    'name' => 'id',
                ],
                'treatment_type' => [
                    'label' => ucfirst(__('treatment type')),
                    'name' => 'treatment_type',
                    'attribute' => 'name',
                ],
                'vet' => [
                    'label' => ucfirst(__('vet')),
                    'name' => 'vetLink',
                ],
                'affected_animals' => [
                    'label' => ucfirst(__('Operations')),
                    'name' => 'affected_animals',
                ],
                'expense' => [
                    'label' => __('Expense'),
                    'name' => 'fullExpense',
                ],
                'date' => [
                    'label' => __('Date'),
                    'name' => 'date',
                ],
            ],
        ];
    }

    private function tableAdoptions()
    {
        return [
            'label' => ucfirst(__('adoptions')),
            'name' => 'adoptions',
            'type' => 'relation_table',
            'route' => '/admin/adoption',
            'buttons' => is('admin', 'adoptions') ? ['add'] : [],
            'columns' => [
                'id' => [
                    'label' => 'ID',
                    'name' => 'id',
                ],
                'user' => [
                    'label' => ucfirst(__('volunteer')),
                    'name' => 'userLink',
                ],
                'name' => [
                    'label' => __('Name'),
                    'name' => 'name',
                ],
                'fat' => [
                    'label' => 'FAT',
                    'name' => 'fatLink',
                ],
                'gender' => [
                    'label' => __('gender'),
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
        ];
    }

    private function tableStats()
    {
        return [
            'label' => __('Stats'),
            'name' => 'stats',
            'type' => 'stats',
            'rows' => [
                'expenses' => [
                    'label' => __('Total Expenses'),
                    'value' => 'getTotalExpensesStats',
                ],
                'operations' => [
                    'label' => __('Total Operations'),
                    'value' => 'getTotalOperationsStats',
                ],
                'donated' => [
                    'label' => __('Total Donated'),
                    'value' => 'getTotalDonatedStats',
                ],
                'donations' => [
                    'label' => __('Total Donations'),
                    'value' => 'getTotalDonationsStats',
                ],
                'balance' => [
                    'label' => __('Balance'),
                    'value' => 'getBalanceStats',
                ],
            ],
        ];
    }

}
