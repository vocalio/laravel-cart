<?php

namespace Vocalio\LaravelCart\Events;

use Illuminate\Queue\SerializesModels;
use Vocalio\LaravelCart\Models\Cart;

class CartItemRemoved
{
    use SerializesModels;

    public function __construct(
        public Cart $cart,
        public mixed $id,
    ) {
        //
    }
}
