<?php

namespace App;

/** @phpstan-ignore trait.unused */
trait PageTemplates
{
    private function home(): void
    {
        $this->addField([
            'name' => 'header',
            'label' => __('Title'),
            'type' => 'text',
        ]);
    }

    // --------------------
    // Helpers
    public function addField($field, $translatable = true): void
    {
        $this->crud->addField(array_merge($field, [
            'fake' => true,
            'store_in' => $translatable ? 'extras_translatable' : 'extras',
        ]));
    }

    private $id = 0;
    public function header($label): void
    {
        $this->crud->addField([
            'name' => 'content_header_'.$this->id++,
            'type' => 'custom_html',
            'value' => "<br/><hr/><h2 style='margin-bottom:-5px'>$label</h2>",
        ]);
    }
}
