<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HandleDropzoneUploadHelper;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
    use HandleDropzoneUploadHelper;

    public function wantsJSON()
    {
        return $this->request && strpos($this->request->headers->get('accept'), 'application/json') === 0;
    }

    public function separator()
    {
        $this->crud->addField([
            'name' => 'separator',
            'type' => 'custom_html',
            'value' => '<hr />',
            'wrapperAttributes' => [
                'style' => 'margin:0'
            ]
        ]);
    }
}