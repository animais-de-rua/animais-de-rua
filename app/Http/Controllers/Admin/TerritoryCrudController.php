<?php

namespace App\Http\Controllers\Admin;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TerritoryRequest as StoreRequest;
use App\Http\Requests\TerritoryRequest as UpdateRequest;
use App\Models\Territory;
use DB;

class TerritoryCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Territory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/territory');
        $this->crud->setEntityNameStrings(__('territory'), __('territories'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->setColumns(['name', 'sf']);

        $this->crud->setColumnDetails('sf', [
            'label' => "Código SF",
        ]);

        // ------ CRUD BUTTONS
        $this->crud->removeAllButtons();

        // ------ CRUD ACCESS
        $this->crud->allowAccess(['list']);
        $this->crud->denyAccess(['create', 'update', 'reorder', 'delete']);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // Filtrers
        $this->crud->addFilter([
            'name' => 'level',
            'type' => 'select2',
            'label'=> ucwords(__("territory")),
        ], [
            1 => __("Distrito"),
            2 => __("Concelho"),
            3 => __("Freguesia"),
        ], function($value) {
            $this->crud->addClause('where', 'level', $value);
        });

        $this->crud->addFilter([
            'name' => 'distrito',
            'type' => 'select2_ajax',
            'label'=> 'Distrito',
            'placeholder' => 'Escolha um distrito'
        ],
        url('admin/territory/list/1'),
        function($value) {
            $this->crud->addClause('where', 'parent_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'concelho',
            'type' => 'select2_ajax',
            'label'=> 'Concelho',
            'placeholder' => 'Escolha um concelho'
        ],
        url('admin/territory/list/2'),
        function($value) {
            $this->crud->addClause('where', 'parent_id', $value);
        });
    }

    public function ajax_list($level = 0)
    {
        $data = [];

        if($level == 0 || $level == 1) {
            $dataC = DB::table('territories as a')
                ->select(DB::raw("a.id, a.name"))
                ->where([['a.level', '=', 1]])->get();
            
            foreach ($dataC as $v)
                $data[$v->id] = $v->name;
        }

        if($level == 0 || $level == 2) {
            $dataB = DB::table('territories as a')
                ->join ('territories as b', 'a.parent_id', '=' , 'b.id')
                ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name) name"))
                ->where([['a.level', '=', 2]])->get();
            
            foreach ($dataB as $v)
                $data[$v->id] = $v->name;
        }

        if($level == 0 || $level == 3) {
            $dataA = DB::table('territories as a')
                ->join ('territories as b', 'a.parent_id', '=' , 'b.id')
                ->join ('territories as c', 'b.parent_id', '=' , 'c.id')
                ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name, ', ', c.name) name"))
                ->where([['a.level', '=', 3]])->get();
            
            foreach ($dataA as $v)
                $data[$v->id] = $v->name;
        }

        return $data;
    }

    public function ajax_search(\Illuminate\Http\Request $request)
    {
        $search_term = $request->input('q');
        $data = [];

        $dataC = DB::table('territories as a')
            ->select(DB::raw("a.id, a.name"))
            ->where([['a.level', '=', 1], ['a.name', 'LIKE', "%$search_term%"]])
            ->limit(2)
            ->get();

        $dataB = DB::table('territories as a')
            ->join('territories as b', 'a.parent_id', '=' , 'b.id')
            ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name) name"))
            ->where([['a.level', '=', 2], ['a.name', 'LIKE', "%$search_term%"]])
            ->limit(4)
            ->get();

        $dataA = DB::table('territories as a')
            ->join('territories as b', 'a.parent_id', '=' , 'b.id')
            ->join('territories as c', 'b.parent_id', '=' , 'c.id')
            ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name, ', ', c.name) name"))
            ->where([['a.level', '=', 3], ['a.name', 'LIKE', "%$search_term%"]])
            ->limit(8)
            ->get();
            
        foreach ($dataC as $v) $data[] = $v;
        foreach ($dataB as $v) $data[] = $v;
        foreach ($dataA as $v) $data[] = $v;

        return ["data" => $data];
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
