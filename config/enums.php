<?php

return [
    'donation' => [
        'status' => [
            'confirmed' => 'confirmed',
            'unconfirmed' => 'unconfirmed',
        ]
    ],

    'process' => [
        'specie' => [
            'dog' => 'dog',
            'cat' => 'cat',
        ],

        'status' => [
            'approving' => 'approving',
            'waiting_godfather' => 'waiting_godfather',
            'waiting_capture' => 'waiting_capture',
            'open' => 'open',
            'closed' => 'closed',
            'archived' => 'archived'
        ]
    ],

    'territory' => [
        'levels' => [
            1 => 'district',
            2 => 'county',
            3 => 'parish'
        ]
    ],

    'vet' => [
        'status' => [
            'active' => 'active',
            'inactive' => 'inactive'
        ]
    ]
];