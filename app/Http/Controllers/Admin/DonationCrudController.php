<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\DonationRequest as StoreRequest;
use App\Http\Requests\DonationRequest as UpdateRequest;
use App\Models\Donation;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

/**
 * Class DonationCrudController
 *
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
        $this->crud->setRoute(config('backpack.base.route_prefix').'/donation');
        $this->crud->setEntityNameStrings(__('donation'), __('donations'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['process_id', 'type', 'godfather_id', 'headquarter_id', 'protocol_id', 'value', 'date']);

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
            'default' => Request::get('process') ?: false,
        ]);

        $this->crud->addField([
            'label' => __('Type'),
            'name' => 'type',
            'type' => 'enum',
            'attributes' => [
                'donation_type' => 'selector',
            ],
        ]);

        $this->separator()->afterField('process_id');

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
            'default' => Request::get('godfather') ?: false,
            'attributes' => [
                'donation_type_select' => 'private',
            ],
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('headquarter')),
            'name' => 'headquarter_id',
            'type' => 'select2',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => 'App\Models\Headquarter',
            'default' => Request::get('headquarter') ?: false,
            'attributes' => [
                'donation_type_select' => 'headquarter',
            ],
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('protocol')),
            'name' => 'protocol_id',
            'type' => 'select2_from_ajax',
            'entity' => 'protocol',
            'attribute' => 'name',
            'model' => '\App\Models\Protocol',
            'data_source' => url('admin/protocol/ajax/search'),
            'placeholder' => __('Select a protocol'),
            'minimum_input_length' => 2,
            'default' => Request::get('protocol') ?: false,
            'attributes' => [
                'donation_type_select' => 'protocol',
            ],
        ]);

        $this->separator()->afterField('protocol_id');

        $this->crud->addField([
            'label' => __('Date'),
            'name' => 'date',
            'type' => 'date',
            'default' => Carbon::today()->toDateString(),
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'name' => 'notes',
            'type' => 'textarea',
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
        $this->crud->addColumns(['id', 'type', 'godfather_id', 'headquarter_id', 'protocol_id', 'process_id', 'value', 'date', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('date', [
            'type' => 'date',
            'label' => __('Date'),
        ]);

        $this->crud->setColumnDetails('type', [
            'type' => 'trans',
            'label' => __('Type'),
        ]);

        $this->crud->setColumnDetails('godfather_id', [
            'label' => ucfirst(__('godfather')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getGodfatherLinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->select('donations.*')
                    ->leftJoin('godfathers', 'godfathers.id', '=', 'donations.godfather_id')
                    ->orderBy('godfathers.name', $direction);
            },
        ]);

        $this->crud->setColumnDetails('headquarter_id', [
            'label' => ucfirst(__('headquarter')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getHeadquarterLinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->select('donations.*')
                    ->leftJoin('headquarters', 'headquarters.id', '=', 'donations.headquarter_id')
                    ->orderBy('headquarters.name', $direction);
            },
        ]);

        $this->crud->setColumnDetails('protocol_id', [
            'label' => ucfirst(__('protocol')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProtocolLinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->selectRaw('donations.*')
                    ->leftJoin('protocols', 'protocols.id', '=', 'donations.protocol_id')
                    ->orderBy('protocols.name', $direction);
            },
        ]);

        $this->crud->setColumnDetails('process_id', [
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute',

            'orderable' => true,
            'orderLogic' => function ($query, $column, $direction) {
                return $query->selectRaw('donations.*')
                    ->leftJoin('processes', 'processes.id', '=', 'donations.process_id')
                    ->orderBy('processes.name', $direction);
            },
        ]);

        $this->crud->setColumnDetails('value', [
            'name' => 'value',
            'label' => __('Value'),
            'type' => 'model_function',
            'function_name' => 'getFullValueAttribute',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // Filtrers
        $this->crud->addFilter([
            'name' => 'type',
            'type' => 'select2',
            'label' => __('Type'),
            'placeholder' => __('Select a type'),
        ],
            EnumHelper::translate('donation.type'),
            function ($value) {
                $this->crud->addClause('where', 'type', $value);
            });

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
            'name' => 'headquarter_type',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('headquarter')).' ('.__('Type').')',
            'placeholder' => __('Select a headquarter'),
        ],
            url('admin/headquarter/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'headquarter_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'headquarter',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('headquarter')).' ('.ucfirst(__('process')).')',
            'placeholder' => __('Select a headquarter'),
        ],
            url('admin/headquarter/ajax/filter'),
            function ($value) {
                $this->crud->addClause('whereHas', 'process', function ($query) use ($value) {
                    $query->where('headquarter_id', $value);
                });
            });

        $this->crud->addFilter([
            'name' => 'protocol',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('protocol')),
            'placeholder' => __('Select a protocol'),
        ],
            url('admin/protocol/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'protocol_id', $value);
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
            url('admin/user/ajax/filter/'.User::ROLE_VOLUNTEER),
            function ($value) {
                $this->crud->addClause('where', 'user_id', $value);
            });

        // ------ CRUD ACCESS
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        if (! is('admin', 'accountancy')) {
            $this->crud->denyAccess(['list', 'create']);
        }

        if (! is('admin')) {
            $this->crud->denyAccess(['delete', 'update']);

            $this->crud->addClause('whereHas', 'process', function ($query) {
                $headquarters = restrictToHeadquarters();
                $query->whereIn('headquarter_id', $headquarters ?: []);
            })->get();
        }

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['process', 'godfather', 'user']);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        // Add asterisk for fields that are required
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function showDetailsRow($id)
    {
        $donation = Donation::select(['notes'])->find($id);

        return "<div style='margin:5px 8px'>
                <p style='white-space: pre-wrap;'><i>".__('Notes')."</i>: $donation->notes</p>
            </div>";
    }

    public function store(StoreRequest $request)
    {
        // Add user
        $request->merge(['user_id' => user()->id]);

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
