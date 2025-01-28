<?php

use Vocalio\LaravelCart\LaravelCart;

if (! function_exists('cart')) {
    function cart(): LaravelCart
    {
        return app(LaravelCart::class);
    }
}
