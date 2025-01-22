<?php

namespace Vocalio\LaravelCart;

use Vocalio\LaravelCart\Models\Cart;

class LaravelCart
{
    use Concerns\InteractsWithCart;
    use Concerns\InteractsWithItems;
    use Concerns\CartCalculations;

    public ?Cart $record;
    public ItemsCollection $items;

    public function __construct()
    {
        $this->cart();
    }

    public function make(): self
    {
        return $this;
    }
}
