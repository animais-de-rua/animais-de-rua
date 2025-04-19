<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HandleDropzoneUploadHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use Illuminate\Http\Request;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
    use HandleDropzoneUploadHelper;
    use Permissions;

    public function wantsJSON()
    {
        return $this->request && strpos($this->request->headers->get('accept'), 'application/json') === 0;
    }

    private $i = 0;
    public function separator($title = '')
    {
        return $this->crud->addField([
            'name' => 'separator'.$this->i++,
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

        return $matches && count($matches) > 1 ? intval($matches[1]) : null;
    }

    // Overrides to deal with cache
    public function storeCrud(?Request $request = null)
    {
        $result = parent::storeCrud($request);
        $this->sync();

        return $result;
    }

    public function updateCrud(?Request $request = null)
    {
        $result = parent::updateCrud($request);
        $this->sync();

        return $result;
    }

    public function saveReorder(?Request $request = null)
    {
        $result = parent::saveReorder($request);
        $this->sync();

        return $result;
    }

    public function destroy($id)
    {
        $result = parent::destroy($id);
        $this->sync();

        return $result;
    }

    public function sync() {}
}
