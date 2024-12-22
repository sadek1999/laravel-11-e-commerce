<?php

namespace App\Enum;

enum PermissionsEnum: string
{
    case ApproveVendors = 'approveVendors';
    case SellProducts = 'sellProducts';

    case BuyProducts = 'buyProducts';
}
