<?php

namespace App;

trait PageTemplates
{
    private function home()
    {
        // --------------------
        $this->header('Associação');

        $this->addField([
            'name' => 'association_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'association_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        $this->addField([
            'name' => 'association_link',
            'label' => 'Link',
            'type' => 'text'
        ]);
    }

    private function association()
    {
        // --------------------
        $this->header('Associação');

        $this->addField([
            'name' => 'association_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'association_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Quem somos');

        $this->addField([
            'name' => 'whoweare_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'whoweare_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Relatório de actividades');

        $this->addField([
            'name' => 'report_year',
            'label' => 'Ano',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'report_link',
            'label' => 'Link',
            'type' => 'browse'
        ]);

        // --------------------
        $this->header('Actuação');

        $this->addField([
            'name' => 'act_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'act_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Programa de controlo');

        $this->addField([
            'name' => 'program_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'program_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Visão');

        $this->addField([
            'name' => 'vision_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'vision_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Missão');

        $this->addField([
            'name' => 'mission_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'mission_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Valores');

        $this->addField([
            'name' => 'values_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'values_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Onde estamos');

        $this->addField([
            'name' => 'where_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'where_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);
    }

    private function ced()
    {

        // --------------------
        $this->header('CED');

        $this->addField([
            'name' => 'ced_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'ced_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('O que é o CED?');

        $this->addField([
            'name' => 'what_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'what_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Informações úteis sobre o CED');

        $this->addField([
            'name' => 'info_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'info_links',
            'label' => "PDF's",
            'type' => 'browse_table',
            'entity_singular' => 'pdf',
            'columns' => [
                'name' => __('Name'),
                'url' => 'URL'
            ],
            'mimes' => ['pdf']
        ]);

        // --------------------
        $this->header('As vantagens do CED');

        $this->addField([
            'name' => 'advantage_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'advantage_community_title',
            'label' => 'Comunidade',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'advantage_community_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        $this->addField([
            'name' => 'advantage_colony_title',
            'label' => 'Colónia',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'advantage_colony_text',
            'label' => '',
            'type' => 'wysiwyg'
        ]);

        // --------------------
        $this->header('Alternativas falhadas ao CED');

        $this->addField([
            'name' => 'alternatives_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'alternatives_text',
            'label' => '',
            'type' => 'textarea'
        ]);

        // --------------------
        $this->header('Captura e Abate');

        $this->addField([
            'name' => 'alternatives_capture_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'alternatives_capture_text',
            'label' => '',
            'type' => 'textarea'
        ]);

        // --------------------
        $this->header('Parar de Alimentar');

        $this->addField([
            'name' => 'alternatives_feed_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'alternatives_feed_text',
            'label' => '',
            'type' => 'textarea'
        ]);

        // --------------------
        $this->header('Acolhimento ou realojamento');

        $this->addField([
            'name' => 'alternatives_greeting_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'alternatives_greeting_text',
            'label' => '',
            'type' => 'textarea'
        ]);

        // --------------------
        $this->header('Não fazer nada');

        $this->addField([
            'name' => 'alternatives_nothing_title',
            'label' => '',
            'type' => 'text'
        ]);

        $this->addField([
            'name' => 'alternatives_nothing_text',
            'label' => '',
            'type' => 'textarea'
        ]);

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

    private $id = 0;
    private function header($label)
    {
        $this->crud->addField([
            'name' => 'content_header_' . $this->id++,
            'type' => 'custom_html',
            'value' => "<br/><hr/><h2 style='margin-bottom:-15px'>$label</h2>"
        ]);
    }
}
