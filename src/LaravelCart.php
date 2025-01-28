<?php

namespace Vocalio\LaravelCart;

use Vocalio\LaravelCart\Models\Cart;

class LaravelCart
{
    use Concerns\CartCalculations;
    use Concerns\InteractsWithCart;
    use Concerns\InteractsWithItems;
    use Concerns\InteractsWithModifiers;

    public ?Cart $record;

    public ItemsCollection $items;

    public ModifierCollection $modifiers;

    public function __construct()
    {
        $this->init();
    }

    public function make(): self
    {
        return $this;
    }
}
