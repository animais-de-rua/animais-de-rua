<?php

namespace App\Http\Controllers\Admin;

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
        $this->crud->addFields(['name', 'contact', 'phone', 'email', 'latlong',/* 'address',*/ 'territory_id', 'headquarter_id', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status', 'images', 'history', 'notes']);

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
            'options' => app('App\Http\Controllers\Admin\TerritoryCrudController')->ajax_list(),
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
            'name' => 'amount_males'
        ]);

        $this->crud->addField([
            'label' => __('Females Amount'),
            'type' => 'number',
            'name' => 'amount_females'
        ]);

        $this->crud->addField([
            'label' => __('Others Amount'),
            'type' => 'number',
            'name' => 'amount_other'
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
            'google_api_key' => 'AIzaSyAci5Qy72upCoNCJTjcY9h9pkYj-TPJkqU',
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


        // ------ CRUD COLUMNS
        $this->crud->addColumns(['name', 'created_at', 'territory_id', 'Headquarter', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status']);

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
            'model' => "App\Models\Territory",
            'link' => true
        ]);

        $this->crud->setColumnDetails('Headquarter', [
            'label' => ucfirst(__('headquarter')),
            'type' => "select",
            'entity' => 'headquarter',
            'attribute' => "name",
            'model' => "App\Models\Headquarter",
            'link' => true
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


        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();
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
        return parent::storeCrud($request);;
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
