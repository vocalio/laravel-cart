<?php

namespace Vocalio\LaravelCart\Concerns;

use Vocalio\LaravelCart\Data\Item;
use Vocalio\LaravelCart\Events\CartItemAdded;
use Vocalio\LaravelCart\Events\CartItemRemoved;
use Vocalio\LaravelCart\Events\CartItemUpdated;
use Vocalio\LaravelCart\ItemsCollection;

trait InteractsWithItems
{
    public function items(): ItemsCollection
    {
        $items = clone $this->items;

        return $items;
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }

    public function isNotEmpty(): bool
    {
        return $this->items()->isNotEmpty();
    }

    public function add(Item $item): self
    {
        // If the item is already in the cart we will just update it
        if ($this->items->contains('id', $item->id)) {
            $this->update($item->id, $item);
        } else {
            $this->items->add($item);

            $this->persist();

            event(new CartItemAdded($this->record, $item));
        }

        return $this;
    }

    public function update(mixed $id, Item $item): self
    {
        $this->items = $this->items()->map(function (Item $cartItem) use ($id, $item) {
            if ($cartItem->id === $id) {
                foreach (get_object_vars($item) as $property => $value) {
                    if ($property == 'quantity') {
                        // If the item is forced to have a specific quantity we will just set it
                        if ($item->forceQuantity) {
                            $cartItem->quantity = $value;
                        } else {
                            $cartItem->quantity += $value;
                        }
                    } else {
                        $cartItem->{$property} = $value;
                    }
                }
            }

            return $cartItem;
        });

        $this->persist();

        event(new CartItemUpdated($this->record, $item));

        return $this;
    }

    public function remove(mixed $id): self
    {
        $this->items = $this->items()->reject(function (Item $item) use ($id) {
            return $item->id === $id;
        });

        $this->persist();

        event(new CartItemRemoved($this->record, $id));

        return $this;
    }
}
