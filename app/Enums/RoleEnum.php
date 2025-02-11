<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case RECEIVINGMANAGER = 'receiving manager';
    case SELECTIONMANAGER = 'ripening & selection manager';
    case COOKINGMANAGER = 'processing cooking manager';
    case PACKAGINGMANAGER = 'packing manager';
    case SUPPLIER = 'supplier';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
