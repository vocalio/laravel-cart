<?php

namespace Vocalio\LaravelCart;

use Illuminate\Support\Collection;
use Vocalio\LaravelCart\Data\Item;

class ItemsCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->items = collect($this->items)->map(function ($item) {
            if ($item instanceof Item) {
                return $item;
            }

            try {
                return Item::fromJson($item);
            } catch (\Exception $e) {
                //
            }
        })
            ->filter()
            ->toArray();
    }

    public function find(mixed $id): Item
    {
        return $this->first(fn (Item $item) => $item->id === $id);
    }
}
