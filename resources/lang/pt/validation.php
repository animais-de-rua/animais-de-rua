<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O campo :attribute deverá ser aceite.',
    'active_url' => 'O campo :attribute não contém um URL válido.',
    'after' => 'O campo :attribute deverá conter uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute deverá conter uma data posterior ou igual a :date.',
    'alpha' => 'O campo :attribute deverá conter apenas letras.',
    'alpha_dash' => 'O campo :attribute deverá conter apenas letras, números e traços.',
    'alpha_num' => 'O campo :attribute deverá conter apenas letras e números.',
    'array' => 'O campo :attribute deverá conter uma coleção de elementos.',
    'before' => 'O campo :attribute deverá conter uma data anterior a :date.',
    'before_or_equal' => 'O Campo :attribute deverá conter uma data anterior ou igual a :date.',
    'between' => [
        'numeric' => 'O campo :attribute deverá ter um valor entre :min - :max.',
        'file' => 'O campo :attribute deverá ter um tamanho entre :min - :max kilobytes.',
        'string' => 'O campo :attribute deverá conter entre :min - :max caracteres.',
        'array' => 'O campo :attribute deverá conter entre :min - :max elementos.',
    ],
    'boolean' => 'O campo :attribute deverá conter o valor verdadeiro ou falso.',
    'confirmed' => 'A confirmação para o campo :attribute não coincide.',
    'date' => 'O campo :attribute não contém uma data válida.',
    'date_equals' => 'O campo :attribute deverá ser uma data igual a :date.',
    'date_format' => 'A data indicada para o campo :attribute não respeita o formato :format.',
    'different' => 'Os campos :attribute e :other deverão conter valores diferentes.',
    'digits' => 'O campo :attribute deverá conter :digits caracteres.',
    'digits_between' => 'O campo :attribute deverá conter entre :min a :max caracteres.',
    'dimensions' => 'O campo :attribute deverá conter uma dimensão de imagem válida.',
    'distinct' => 'O campo :attribute contém um valor duplicado.',
    'email' => 'O campo :attribute não contém um endereço de correio eletrónico válido.',
    'exists' => 'O valor selecionado para o campo :attribute é inválido.',
    'file' => 'O campo :attribute deverá conter um ficheiro.',
    'filled' => 'É obrigatória a indicação de um valor para o campo :attribute.',
    'gt' => [
        'numeric' => 'O campo :attribute deverá ser maior que :value.',
        'file' => 'O campo :attribute deverá ser maior que :value kilobytes.',
        'string' => 'O campo :attribute deverá ter :value caracteres ou mais.',
        'array' => 'O campo :attribute deverá ser conter mais que :value itens.',
    ],
    'gte' => [
        'numeric' => 'O campo :attribute deverá ser maior ou igual a :value.',
        'file' => 'O campo :attribute deverá ser maior ou igual :value kilobytes.',
        'string' => 'O campo :attribute deverá ter maior ou igual a :value caracteres.',
        'array' => 'O campo :attribute deverá ter :value ou mais itens.',
    ],
    'image' => 'O campo :attribute deverá conter uma imagem.',
    'in' => 'O campo :attribute não contém um valor válido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O campo :attribute deverá conter um número inteiro.',
    'ip' => 'O campo :attribute deverá conter um IP válido.',
    'ipv4' => 'O campo :attribute deverá conter um IPv4 válido.',
    'ipv6' => 'O campo :attribute deverá conter um IPv6 válido.',
    'json' => 'O campo :attribute deverá conter um texto JSON válido.',
    'lt' => [
        'numeric' => 'O campo :attribute deverá ser menor que :value.',
        'file' => 'O campo :attribute deverá ser menor que :value kilobytes.',
        'string' => 'O campo :attribute deverá ter menos do que :value caracteres.',
        'array' => 'O campo :attribute deverá ter menos do que :value itens.',
    ],
    'lte' => [
        'numeric' => 'O campo :attribute deverá ser menor ou igual a :value.',
        'file' => 'O campo :attribute deverá ser menor ou igual a :value kilobytes.',
        'string' => 'O campo :attribute deverá ter :value caracteres ou menos.',
        'array' => 'O campo :attribute deverá ter :value itens ou menos.',
    ],
    'max' => [
        'numeric' => 'O campo :attribute não deverá conter um valor superior a :max.',
        'file' => 'O campo :attribute não deverá ter um tamanho superior a :max kilobytes.',
        'string' => 'O campo :attribute não deverá conter mais de :max caracteres.',
        'array' => 'O campo :attribute não deverá conter mais de :max elementos.',
    ],
    'mimes' => 'O campo :attribute deverá conter um ficheiro do tipo: :values.',
    'mimetypes' => 'O campo :attribute deverá conter um ficheiro do tipo: :values.',
    'min' => [
        'numeric' => 'O campo :attribute deverá ter um valor superior ou igual a :min.',
        'file' => 'O campo :attribute deverá ter no mínimo :min kilobytes.',
        'string' => 'O campo :attribute deverá conter no mínimo :min caracteres.',
        'array' => 'O campo :attribute deverá conter no mínimo :min elementos.',
    ],
    'not_in' => 'O campo :attribute contém um valor inválido.',
    'numeric' => 'O campo :attribute deverá conter um valor numérico.',
    'not_regex' => 'O formato do valor para o campo :attribute é inválido.',
    'present' => 'O campo :attribute deverá estar presente.',
    'regex' => 'O formato do valor para o campo :attribute é inválido.',
    'required' => 'É obrigatória a indicação de um valor para o campo :attribute.',
    'required_if' => 'É obrigatória a indicação de um valor para o campo :attribute quando o valor do campo :other é igual a :value.',
    'required_unless' => 'É obrigatória a indicação de um valor para o campo :attribute a menos que :other esteja presente em :values.',
    'required_with' => 'É obrigatória a indicação de um valor para o campo :attribute quando :values está presente.',
    'required_with_all' => 'É obrigatória a indicação de um valor para o campo :attribute quando um dos :values está presente.',
    'required_without' => 'É obrigatória a indicação de um valor para o campo :attribute quando :values não está presente.',
    'required_without_all' => 'É obrigatória a indicação de um valor para o campo :attribute quando nenhum dos :values está presente.',
    'same' => 'Os campos :attribute e :other deverão conter valores iguais.',
    'size' => [
        'numeric' => 'O campo :attribute deverá conter o tamanho de :size.',
        'file' => 'O campo :attribute deverá ter o tamanho de :size kilobytes.',
        'string' => 'O campo :attribute deverá conter :size caracteres.',
        'array' => 'O campo :attribute deverá conter :size elementos.',
    ],
    'starts_with' => 'O campo :attribute deverá começar com um dos seguintes valores: :values',
    'string' => 'O campo :attribute deverá conter texto.',
    'timezone' => 'O campo :attribute deverá ter um fuso horário válido.',
    'unique' => 'O valor indicado para o campo :attribute já se encontra registado.',
    'uploaded' => 'O upload do ficheiro :attribute falhou.',
    'url' => 'O formato do URL indicado para o campo :attribute é inválido.',
    'uuid' => 'O campo :attribute deverá conter um UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'address' => 'morada',
        'adoption_id' => 'adopção',
        'affected_animals' => 'animais intervencionados',
        'age' => 'idade',
        'age.1' => 'idade (anos)',
        'age.2' => 'idade (meses)',
        'alias' => 'alias',
        'amount' => 'quantidade',
        'amount_females' => 'fêmeas',
        'amount_males' => 'machos',
        'amount_other' => 'outros',
        'animals' => 'animais',
        'animal_temper' => 'temperamento do animal',
        'benefit' => 'benefício',
        'contact' => 'contacto',
        'council' => 'concelho',
        'date' => 'data',
        'date_1' => 'data',
        'date_2' => 'data',
        'description' => 'descrição',
        'donation_id' => 'donativo',
        'email' => 'email',
        'expense' => 'despesa',
        'fat_id' => 'fat',
        'file' => 'ficheiro',
        'final_date' => 'data de fim',
        'first_name' => 'primeiro nome',
        'gender' => 'género',
        'godfather_id' => 'padrinho',
        'has_walk' => 'passear cães',
        'headquarter_id' => 'núcleo',
        'headquarters' => 'núcleos',
        'history' => 'história',
        'image' => 'imagem',
        'initial_date' => 'data de início',
        'introduction' => 'introdução',
        'last_name' => 'último nome',
        'latlong' => 'localização',
        'name' => 'nome',
        'notes' => 'notas',
        'number_of_animals' => 'nº de animais',
        'paypal_code' => 'código paypal',
        'phone' => 'telefone',
        'phone1' => 'telefone',
        'phone2' => 'telefone',
        'process_id' => 'processo',
        'specie' => 'especie',
        'status' => 'estado',
        'sterilized' => 'esterilizado',
        'territory_id' => 'território',
        'treatment_id' => 'tratamento',
        'treatment_type_id' => 'tipo de tratamento',
        'type' => 'tipo',
        'url' => 'url',
        'user_id' => 'voluntário',
        'vaccinated' => 'vacinado',
        'value' => 'valor',
        'vet_id' => 'veterinário',
        'vet_id_1' => 'veterinário',
        'vet_id_2' => 'veterinário',
        'vist_number' => 'visitas diárias',
        'images' => 'imagens',
        'images.*' => 'imagens',
        'notes_deliver' => 'Notas: Quem entrega o animal na clínica',
        'notes_collect' => 'Notas: Quem vai buscar o animal ao final do dia',
        'notes_contact' => 'Notas: O teu contacto telefónico',
        'notes_godfather' => 'Notas: Quem apadrinha o animal',
        'protocol_id' => 'protocolo',
        'invoice' => 'fatura',
        'walk_number' => 'passeios diários',
    ],

    'message' => 'Os dados fornecidos são inválidos.',
];
