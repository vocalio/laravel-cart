<?php

namespace Vocalio\LaravelCart;

use Illuminate\Support\Collection;
use Vocalio\LaravelCart\Data\Item;

class ItemsCollection extends Collection
{
    public function parse(array $items = []): self
    {
        $this->items = collect($items)->map(function ($item) {
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

        return $this;
    }

    public function find(mixed $id): ?Item
    {
        return $this->first(fn (Item $item) => $item->id == $id);
    }
}
