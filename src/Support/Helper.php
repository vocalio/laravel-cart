<?php

namespace Vocalio\LaravelCart\Support;

use Illuminate\Support\Number;

class Helper
{
    public static function format(float $value, bool $withCurrency = false): float|string
    {
        $value = round($value, 2);

        if ($withCurrency) {
            $value = Number::currency($value);
        }

        return $value;
    }
}
