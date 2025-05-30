<?php

namespace Vocalio\LaravelCart\Events;

use Illuminate\Queue\SerializesModels;
use Vocalio\LaravelCart\Models\Cart;

class CartSaved
{
    use SerializesModels;

    public function __construct(
        public Cart $cart
    ) {
        //
    }
}
