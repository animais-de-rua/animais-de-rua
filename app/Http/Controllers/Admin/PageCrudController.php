<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use Backpack\PageManager\app\Http\Requests\PageRequest as UpdateRequest;

class PageCrudController extends \Backpack\PageManager\app\Http\Controllers\Admin\PageCrudController
{
    use Permissions;

    public function setup($template_name = false)
    {
        parent::setup($template_name);

        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');

    }

    public function addDefaultPageFields($template = false)
    {
        $result = parent::addDefaultPageFields($template);
        $this->crud->modifyField('template', ['disabled' => 'disabled']);
        $this->crud->modifyField('slug', ['attributes' => ['readonly' => 'readonly'], 'hint' => null]);

        return $result;
    }

    public function update(UpdateRequest $request)
    {
        \Cache::forget('page_' . $request->slug);
        return parent::update($request);
    }
}
