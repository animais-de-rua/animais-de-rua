<?php

namespace App;

trait PageTemplates
{
    private function home(): void
    {
        // --------------------
        $this->header('Associação');

        $this->addField([
            'name' => 'association_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'association_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        $this->addField([
            'name' => 'association_link',
            'label' => 'Link',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Ajudas Urgentes');

        $this->addField([
            'name' => 'processes_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'processes_subtitle',
            'label' => 'sub-título',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Loja');

        $this->addField([
            'name' => 'products_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'products_subtitle',
            'label' => 'sub-título',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Como ajudar?');

        $this->addField([
            'name' => 'help_title',
            'label' => '',
            'type' => 'text',
        ]);

        // --------------------
        $this->addField([
            'name' => 'help_volunteer_title',
            'label' => 'Ser Voluntário',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'help_volunteer_text',
            'label' => '',
            'type' => 'textarea',
        ]);

        // --------------------
        $this->addField([
            'name' => 'help_friend_title',
            'label' => 'Ser nosso Amigo',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'help_friend_text',
            'label' => '',
            'type' => 'textarea',
        ]);

        // --------------------
        $this->addField([
            'name' => 'help_godfather_title',
            'label' => 'Apadrinhar',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'help_godfather_text',
            'label' => '',
            'type' => 'textarea',
        ]);

        // --------------------
        $this->addField([
            'name' => 'help_donate_title',
            'label' => 'Doar',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'help_donate_text',
            'label' => '',
            'type' => 'textarea',
        ]);
    }

    private function association(): void
    {
        // --------------------
        $this->header('Associação');

        $this->addField([
            'name' => 'association_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'association_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Quem somos');

        $this->addField([
            'name' => 'whoweare_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'whoweare_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Relatório de actividades');

        for ($i = 0; $i < 5; $i++) {
            $this->addField([
                'name' => "report_year_$i",
                'label' => '— '.($i + 1).' —<br />Ano',
                'type' => 'number',
            ]);

            $this->addField([
                'name' => "report_link_$i",
                'label' => 'Link',
                'type' => 'browse',
            ]);
        }

        // --------------------
        $this->header('Estatutos');

        $this->addField([
            'name' => 'statutes_year',
            'label' => 'Ano',
            'type' => 'number',
        ]);

        $this->addField([
            'name' => 'statutes_link',
            'label' => 'Link',
            'type' => 'browse',
        ]);

        // --------------------
        $this->header('Orgãos Sociais');

        $this->addField([
            'name' => 'entities_year',
            'label' => 'Ano',
            'type' => 'number',
        ]);

        $this->addField([
            'name' => 'entities_link',
            'label' => 'Link',
            'type' => 'browse',
        ]);

        // --------------------
        $this->header('Actuação');

        $this->addField([
            'name' => 'act_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'act_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Programa de controlo');

        $this->addField([
            'name' => 'program_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'program_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Visão');

        $this->addField([
            'name' => 'vision_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'vision_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Missão');

        $this->addField([
            'name' => 'mission_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'mission_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Valores');

        $this->addField([
            'name' => 'values_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'values_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Onde estamos');

        $this->addField([
            'name' => 'where_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'where_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);
    }

    private function ced(): void
    {
        // --------------------
        $this->header('CED');

        $this->addField([
            'name' => 'ced_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'ced_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('O que é o CED?');

        $this->addField([
            'name' => 'what_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'what_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Informações úteis sobre o CED');

        $this->addField([
            'name' => 'info_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'info_links',
            'label' => "PDF's",
            'type' => 'browse_table',
            'entity_singular' => 'pdf',
            'columns' => [
                'name' => __('Name'),
                'url' => 'URL',
            ],
            'mimes' => ['pdf'],
        ]);

        // --------------------
        $this->header('As vantagens do CED');

        $this->addField([
            'name' => 'advantage_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'advantage_community_title',
            'label' => 'Comunidade',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'advantage_community_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        $this->addField([
            'name' => 'advantage_colony_title',
            'label' => 'Colónia',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'advantage_colony_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Alternativas falhadas ao CED');

        $this->addField([
            'name' => 'alternatives_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'alternatives_text',
            'label' => '',
            'type' => 'textarea',
        ]);

        // --------------------
        $this->header('Captura e Abate');

        $this->addField([
            'name' => 'alternatives_capture_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'alternatives_capture_text',
            'label' => '',
            'type' => 'textarea',
        ]);

        // --------------------
        $this->header('Parar de Alimentar');

        $this->addField([
            'name' => 'alternatives_feed_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'alternatives_feed_text',
            'label' => '',
            'type' => 'textarea',
        ]);

        // --------------------
        $this->header('Acolhimento ou realojamento');

        $this->addField([
            'name' => 'alternatives_greeting_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'alternatives_greeting_text',
            'label' => '',
            'type' => 'textarea',
        ]);

        // --------------------
        $this->header('Não fazer nada');

        $this->addField([
            'name' => 'alternatives_nothing_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'alternatives_nothing_text',
            'label' => '',
            'type' => 'textarea',
        ]);
    }

    private function animals(): void
    {
        // --------------------
        $this->header('Animais');

        $this->addField([
            'name' => 'animals_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'animals_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);
    }

    private function help(): void
    {
        // --------------------
        $this->header('Como Ajudar?');

        $this->addField([
            'name' => 'help_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'help_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Ser Voluntário');

        $this->addField([
            'name' => 'volunteer_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'volunteer_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        $this->addField([
            'name' => 'volunteer_link',
            'label' => 'Link',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Adoptar ou Apadrinhar');

        $this->addField([
            'name' => 'adopt_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'adopt_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        $this->addField([
            'name' => 'adopt_link',
            'label' => 'Link',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Ser nosso Amigo');

        $this->addField([
            'name' => 'friend_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'friend_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        $this->addField([
            'name' => 'friend_link',
            'label' => 'Link',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Conhecer a nossa Loja');

        $this->addField([
            'name' => 'store_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'store_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        $this->addField([
            'name' => 'store_link',
            'label' => 'Link',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Doar');

        $this->addField([
            'name' => 'donate_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'donate_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        $this->addField([
            'name' => 'donate_link',
            'label' => 'Link',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Ligar-nos');

        $this->addField([
            'name' => 'call_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'call_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Tornar-se Sócio');

        $this->addField([
            'name' => 'associate_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'associate_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);
    }

    private function partners(): void
    {
        // --------------------
        $this->header('Parceiros');

        $this->addField([
            'name' => 'partners_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'partners_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

    }

    private function petsitting(): void
    {
        // --------------------
        $this->header('Petsitting');

        $this->addField([
            'name' => 'petsitting_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'petsitting_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

    }

    private function friends(): void
    {
        // --------------------
        $this->header('Amigos');

        $this->addField([
            'name' => 'friend_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'friend_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Cartão amigo');

        $this->addField([
            'name' => 'card_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'card_text',
            'label' => '',
            'type' => 'wysiwyg',
        ]);

        // --------------------
        $this->header('Modalidades De Adesão Ao Cartão');

        $this->addField([
            'name' => 'modalities_title',
            'label' => '',
            'type' => 'text',
        ]);

        // --------------------
        $this->header('Conheça As Vantagens Do Nosso Cartão');

        $this->addField([
            'name' => 'advantages_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'advantages_map',
            'label' => 'Mapa',
            'type' => 'text',
        ]);
    }

    private function privacypolicy(): void
    {
        // --------------------
        $this->header('Política de Privacidade');

        $this->addField([
            'name' => 'privacypolicy_title',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'privacypolicy_subtitle',
            'label' => '',
            'type' => 'text',
        ]);

        $this->addField([
            'name' => 'privacypolicy_text',
            'label' => '',
            'type' => 'wysiwyg',
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

    private int $id = 0;
    public function header($label): void
    {
        $this->crud->addField([
            'name' => 'content_header_'.$this->id++,
            'type' => 'custom_html',
            'value' => "<br/><hr/><h2 style='margin-bottom:-15px'>$label</h2>",
        ]);
    }
}
