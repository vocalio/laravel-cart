<?php

namespace Vocalio\LaravelCart\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vocalio\LaravelCart\LaravelCart
 */
class LaravelCart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Vocalio\LaravelCart\LaravelCart::class;
    }
}
