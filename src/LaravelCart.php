<?php

namespace Vocalio\LaravelCart;

use Vocalio\LaravelCart\Models\Cart;

class LaravelCart
{
    use Concerns\CartCalculations;
    use Concerns\InteractsWithCart;
    use Concerns\InteractsWithItems;

    public ?Cart $record;

    public ItemsCollection $items;

    public function __construct()
    {
        $this->init();
    }

    public function make(): self
    {
        return $this;
    }
}
