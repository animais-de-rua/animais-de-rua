<?php

namespace App;

trait PageTemplates
{
    private function home()
    {
        $this->addField([
            'name' => 'association_title',
            'label' => 'Associação',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'association_text',
            'label' => '',
            'type' => 'textarea'
        ]);

        $this->addField([
            'name' => 'association_link',
            'label' => 'Link',
            'type' => 'text'
        ]);
    }

    private function association()
    {

    }

    private function ced()
    {

    }

    private function animals()
    {

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

    // --------------------
    // Helpers
    private function addField($field, $translatable = true)
    {
        $this->crud->addField(array_merge($field, [
            'fake' => true,
            'store_in' => $translatable ? 'extras_translatable' : 'extras'
        ]));
    }
}
