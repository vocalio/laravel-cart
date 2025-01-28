<?php

namespace Vocalio\LaravelCart\Events;

use Illuminate\Queue\SerializesModels;
use Vocalio\LaravelCart\Data\Modifier;
use Vocalio\LaravelCart\Models\Cart;

class CartModifierUpdated
{
    use SerializesModels;

    public function __construct(
        public Cart $cart,
        public Modifier $modifier,
    ) {
        //
    }
}
