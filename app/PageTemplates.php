<?php

namespace App;

trait PageTemplates
{
    private function home()
    {

    }

    private function association()
    {

    }

    private function ced()
    {

    }

    private function animals()
    {
        $this->crud->addField([
            'name' => 'content',
            'label' => __("Content"),
            'type' => 'wysiwyg',
            'placeholder' => trans('backpack::pagemanager.content_placeholder'),
        ]);
    }

    private function help()
    {

    }

    private function partners()
    {

    }

    private function friends()
    {

    }
}
