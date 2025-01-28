<?php

namespace Vocalio\LaravelCart;

use Illuminate\Support\Collection;
use Vocalio\LaravelCart\Data\Item;
use Vocalio\LaravelCart\Support\Helper;

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

    public function getSubTotal(): Helper
    {
        $sumWithVat = $this->sum(function (Item $item) {
            return $item->getTotalPrice()->withVat()->value();
        });

        $sum = $this->sum(function (Item $item) {
            return $item->getTotalPrice()->value();
        });

        return Helper::make()
            ->setValue($sum)
            ->setVatValue($sumWithVat - $sum);
    }

    public function getTotal(): Helper
    {
        $sumWithVat = $this->sum(function (Item $item) {
            return $item->getTotalPrice()->withVat()->value();
        });

        $sum = $this->sum(function (Item $item) {
            return $item->getTotalPrice()->value();
        });

        return Helper::make()
            ->setValue($sum)
            ->setVatValue($sumWithVat - $sum);
    }

    public function getQuantity(): int
    {
        return $this->sum(function (Item $item) {
            return $item->getQuantity();
        });
    }

    public function find(mixed $id): ?Item
    {
        return $this->first(fn (Item $item) => $item->id == $id);
    }
}
