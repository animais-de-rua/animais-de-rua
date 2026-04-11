<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HandleDropzoneUploadHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
    use Permissions;
    use HandleDropzoneUploadHelper;
    use ListOperation;
    use CreateOperation { store as crudStore; }
    use UpdateOperation { update as crudUpdate; }
    use DeleteOperation { destroy as crudDestroy; }

    public function wantsJSON()
    {
        return $this->request && strpos($this->request->headers->get('accept'), 'application/json') === 0;
    }

    private $i = 0;
    public function separator($title = '')
    {
        return $this->crud->addField([
            'name' => 'separator' . $this->i++,
            'type' => 'custom_html',
            'value' => $title ? "<hr /><h2>$title</h2>" : '<hr />',
            'wrapperAttributes' => [
                'style' => 'margin:0',
            ],
        ]);
    }

    public function getEntryID()
    {
        preg_match('/\w+\/(\d+)/', $_SERVER['REQUEST_URI'], $matches);
        return $matches && sizeof($matches) > 1 ? intval($matches[1]) : null;
    }

    // Overrides to call sync() after every mutation
    public function store()
    {
        $result = $this->crudStore();
        $this->sync();

        return $result;
    }

    public function update()
    {
        $result = $this->crudUpdate();
        $this->sync();

        return $result;
    }

    // saveReorder() is only available when ReorderOperation is used in a concrete controller.
    // Individual controllers that need reorder should add:
    //   use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation { saveReorder as crudSaveReorder; }
    // and override saveReorder() calling $this->crudSaveReorder() + sync().

    public function destroy($id)
    {
        $result = $this->crudDestroy($id);
        $this->sync();

        return $result;
    }

    public function sync()
    {}
}
