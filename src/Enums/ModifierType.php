<?php

namespace Vocalio\LaravelCart\Enums;

enum ModifierType: string
{
    case Discount = 'DISCOUNT';
    case Tax = 'TAX';
    case Shipping = 'SHIPPING';
    case Extra = 'EXTRA';
}
