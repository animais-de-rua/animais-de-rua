<?php

return [
    'general' => [
        'boolean' => [
            1 => 'yes',
            0 => 'no',
        ],
        'check' => [
            1 => 'active',
            0 => 'inactive',
        ],
        'friend_card_modalities' => [
            'monthly' => 'monthly',
            'yearly' => 'yearly',
        ],
    ],

    'adoption' => [
        'status' => [
            'open' => 'open',
            'pending' => 'pending',
            'closed' => 'closed',
            'archived' => 'archived',
        ],
    ],

    'animal' => [
        'gender' => [
            'male' => 'male',
            'female' => 'female',
        ],
    ],

    'appointment' => [
        'status' => [
            'approving' => 'approving',
            'approved_option_1' => 'approved_option_1',
            'approved_option_2' => 'approved_option_2',
        ],
    ],

    'donation' => [
        'type' => [
            'private' => 'private',
            'headquarter' => 'headquarter',
            'protocol' => 'protocol',
        ],
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
            'archived' => 'archived',
        ],
    ],

    'treatment' => [
        'status' => [
            'approving' => 'approving',
            'approved' => 'approved',
        ],
    ],

    'territory' => [
        'levels' => [
            1 => 'district',
            2 => 'county',
            3 => 'parish',
        ],
    ],

    'vet' => [
        'status' => [
            'active' => 'active',
            'inactive' => 'inactive',
        ],
    ],

    'user' => [
        'status' => [
            1 => 'active',
            0 => 'inactive',
        ],
        'roles' => [
            1 => 'admin',
            2 => 'volunteer',
            3 => 'store',
            4 => 'translator',
            5 => 'friend card',
        ],
        'permissions' => [
            1 => 'processes',
            2 => 'appointments',
            3 => 'treatments',
            4 => 'adoptions',
            5 => 'accountancy',
            6 => 'website',
            // 7 => 'store',
            // 8 => 'friend card',
            9 => 'vets',
            10 => 'protocols',
            11 => 'council',
            12 => 'store orders',
            13 => 'store shippments',
            14 => 'store stock',
            15 => 'store transaction',
            17 => 'store vouchers',
            16 => 'reports',
        ],
    ],

    'forms' => [
        'themes' => [
            'adoption' => 'adoption',
            'sterilization' => 'sterilization',
        ],
    ],

    'store' => [
        'order' => [
            'waiting' => 'waiting',
            'in_progress' => 'in_progress',
            'shipped' => 'shipped',
        ],
        'transaction' => [
            -1 => 'debit',
            1 => 'credit',
        ],
        'supplier' => [
            'waiting_payment' => 'waiting_payment',
            'paid_out' => 'paid_out',
        ],
        'payment' => [
            'bank_transfer' => 'bank_transfer',
            'paypal' => 'paypal',
            'mbway' => 'mbway',
            'credit_card' => 'credit_card',
        ],
        'voucher' => [
            'used' => 'used',
            'unused' => 'unused',
        ],
    ],
];
