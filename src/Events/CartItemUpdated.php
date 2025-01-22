<?php

namespace Vocalio\LaravelCart\Events;

use Illuminate\Queue\SerializesModels;
use Vocalio\LaravelCart\Data\Item;
use Vocalio\LaravelCart\Models\Cart;

class CartItemUpdated
{
    use SerializesModels;

    public function __construct(
        public Cart $cart,
        public Item $item,
    ) {
        //
    }
}
