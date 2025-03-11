<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum PermissionsEnum: string
{
    use Values;

    case PROCESSES = 'processes';
    case APPOINTMENTS = 'appointments';
    case TREATMENTS = 'treatments';
    case ADOPTIONS = 'adoptions';
    case ACCOUNTANCY = 'accountancy';
    case WEBSITE = 'website';
    case VETS = 'vets';
    case PROTOCOLS = 'protocols';
    case COUNCIL = 'council';
    case STORE_ORDERS = 'store orders';
    case STORE_SHIPMENTS = 'store shippments';
    case STORE_STOCK = 'store stock';
    case STORE_TRANSACTION = 'store transaction';
    case STORE_VOUCHERS = 'store vouchers';
    case REPORTS = 'reports';

}
