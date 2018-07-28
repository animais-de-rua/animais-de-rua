<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Helpers\EnumHelper;
use App\Models\Process;
use App\Http\Requests\ProcessRequest as StoreRequest;
use App\Http\Requests\ProcessRequest as UpdateRequest;

class ProcessCrudController extends CrudController
{
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

        //$this->crud->setFromDb();

        // ------ CRUD FIELDS
        $this->crud->addFields(['name', 'contact', 'phone', 'email', 'latlong',/* 'address',*/ 'territory_id', 'headquarter_id', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status', 'images', 'history', 'notes', 'donations']);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name'
        ]);

        $this->crud->addField([
            'label' => __('Contact'),
            'name' => 'contact'
        ]);

        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone'
        ]);

        $this->crud->addField([
            'label' => __('Email'),
            'type' => 'email',
            'name' => 'email'
        ]);

        /*$this->crud->addField([
            'label' => __('Address'),
            'type' => 'textarea',
            'name' => 'address'
        ]);*/

        $this->crud->addField([
            'label' => ucfirst(__("territory")),
            'name' => 'territory_id',
            'type' => 'select2_from_array',
            'options' => $this->wantsJSON() ? null : api()->territoryList(),
            'allows_null' => true,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__("headquarter")),
            'name' => 'headquarter_id',
            'type' => 'select2',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => 'App\Models\Headquarter'
        ]);

        $this->crud->addField([
            'label' => __('Specie'),
            'type' => 'enum',
            'name' => 'specie'
        ]);

        $this->crud->addField([
            'label' => __('Males Amount'),
            'type' => 'number',
            'name' => 'amount_males',
            'default' => 0
        ]);

        $this->crud->addField([
            'label' => __('Females Amount'),
            'type' => 'number',
            'name' => 'amount_females',
            'default' => 0
        ]);

        $this->crud->addField([
            'label' => __('Others Amount'),
            'type' => 'number',
            'name' => 'amount_other',
            'default' => 0
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status'
        ]);

        $this->crud->addField([
            'label' => __('Location'),
            'type' => 'latlng',
            'name' => 'latlong',
            'map_style' => 'width:100%; height:320px;',
            'google_api_key' => env('GOOGLE_API_KEY'),
            'default_zoom' => '9'
        ]);

        $this->crud->addField([
            'label' => __('History'),
            'type' => 'wysiwyg',
            'name' => 'history'
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'type' => 'wysiwyg',
            'name' => 'notes'
        ]);

        $this->crud->addField([
            'name' => 'images',
            'label' => __('Images'),
            'type' => 'dropzone',
            'upload-url' => '/admin/dropzone/images/process',
        ]);

        $this->separator();

        $this->crud->addField([
            'label' => ucfirst(__('donations')),
            'name' => 'donations',
            'type' => 'relation_table',
            'route' => '/admin/donation',
            'columns' => [
                'name' => [
                    'label' => ucfirst(__('godfather')),
                    'name' => 'godfatherLink',
                ],
                'value' => [
                    'label' => __('Value'),
                    'name' => 'fullValue',
                ],
                'status' => [
                    'label' => __('Status'),
                    'name' => 'fullStatus',
                ],
                'date' => [
                    'label' => __('Date'),
                    'name' => 'date',
                ]
            ]
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'territory_id', 'headquarter', 'created_at', 'specie', 'amount_males', 'amount_females', 'amount_other', 'donations', 'status']);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name')
        ]);

        $this->crud->setColumnDetails('created_at', [
            'type' => "date",
            'label' => __('Date'),
        ]);

        $this->crud->setColumnDetails('territory_id', [
            'label' => ucfirst(__('territory')),
            'type' => "select",
            'entity' => 'territory',
            'attribute' => "name",
            'model' => "App\Models\Territory"
        ]);

        $this->crud->setColumnDetails('headquarter', [
            'label' => ucfirst(__('headquarter')),
            'type' => "select",
            'entity' => 'headquarter',
            'attribute' => "name",
            'model' => "App\Models\Headquarter"
        ]);

        $this->crud->setColumnDetails('specie', [
            'type' => 'trans',
            'label' => __('Specie')
        ]);

        $this->crud->setColumnDetails('amount_males', [
            'label' => __('Males Amount')
        ]);

        $this->crud->setColumnDetails('amount_females', [
            'label' => __('Females Amount')
        ]);

        $this->crud->setColumnDetails('amount_other', [
            'label' => __('Others Amount')
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status')
        ]);

        $this->crud->setColumnDetails('donations', [
            'name' => 'donations',
            'label' => __("Total Donated"),
            'type' => "model_function",
            'function_name' => 'getTotalDonatedValue'
        ]);

        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();

        // Filtrers
        $this->crud->addFilter([
            'name' => 'territory_id',
            'type' => 'select2_multiple',
            'label'=> ucfirst(__("territory")),
            'placeholder' => __('Select a territory')
        ],
        $this->wantsJSON() ? null : api()->territoryList(),
        function($values) {
            $values = json_decode($values);
            $where = join(' OR ', array_fill(0, count($values), "territory_id LIKE ?"));
            $values = array_map(function($field) { return $field . "%"; }, $values);

            $this->crud->query->whereRaw($where, $values);
        });

        $this->crud->addFilter([
            'name' => 'headquarter_id',
            'type' => 'select2_multiple',
            'label'=> ucfirst(__("headquarter")),
            'placeholder' => __('Select a headquarter')
        ],
        $this->wantsJSON() ? null : api()->headquarterList(),
        function($values) {
            $this->crud->addClause('whereIn', 'headquarter_id', json_decode($values));
        });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> __('Date range'),
            'format'=> "DD/MM/YYYY",
            'firstDay'=> 1
        ],
        false,
        function($value) {
            $dates = json_decode($value);
            $this->crud->query->whereRaw("created_at >= ? AND created_at <= DATE_ADD(?, INTERVAL 1 DAY)", [$dates->from, $dates->to]);
        });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label'=> __("Status"),
            'placeholder' => __('Select a status')
        ],
        EnumHelper::translate('process.status'),
        function($values) {
            $this->crud->addClause('whereIn', 'status', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'specie',
            'type' => 'select2_multiple',
            'label'=> __("Specie"),
            'placeholder' => __('Select a specie')
        ],
        EnumHelper::translate('process.specie'),
        function($values) {
            $this->crud->addClause('whereIn', 'specie', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'value',
            'type' => 'range',
            'label'=> __('Animal count'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value')
        ],
        true,
        function($value) {
            $range = json_decode($value);
            if ($range->from) $this->crud->addClause('where', DB::raw('amount_males + amount_females + amount_other'), '>=', $range->from);
            if ($range->to) $this->crud->addClause('where', DB::raw('amount_males + amount_females + amount_other'), '<=', $range->to);
        });

        $this->crud->addFilter([
            'name' => 'donations',
            'type' => 'range',
            'label'=> ucfirst(__('donations')) . ' €',
            'label_from' => __('Min value'),
            'label_to' => __('Max value')
        ],
        true,
        function($value) {
            $range = json_decode($value);

            $this->crud->query->whereHas('donations', function ($query) use ($range) {
                $query->selectRaw("process_id, sum(value) as total")
                    ->where('donations.status', 'LIKE', 'confirmed')
                    ->groupBy(['process_id']);

                if ($range->from) $query->having('total', '>=', $range->from);
                if ($range->to) $query->having('total', '<=', $range->to);
            });
        });

        // ------ ADVANCED QUERIES
        $this->crud->addClause('with', ['donations' => function ($query) {
            $query->selectRaw("process_id, sum(value) as total")
                ->where('donations.status', 'LIKE', 'confirmed')
                ->groupBy(['process_id']);
        }]);
    }

    public function showDetailsRow($id)
    {
        $process = \DB::table('processes')->select(['history', 'notes', 'contact', 'phone', 'email'])->where("id", "=", $id)->first();
        
        return "<div style='margin:5px 8px'>
                <p>$process->contact, $process->phone<br />$process->email</p>
                <p>$process->history</p>
                <p>$process->notes</p>
            </div>";
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
