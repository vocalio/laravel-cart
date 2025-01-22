<?php

namespace Vocalio\LaravelCart\Concerns;

use Vocalio\LaravelCart\Data\Item;
use Vocalio\LaravelCart\Support\Helper;

trait CartCalculations
{
    public function getTotal(bool $withCurrency = false): float|string
    {
        $sum = $this->items()->sum(function (Item $item) {
            return $item->getTotalPrice();
        });

        return Helper::format($sum, ...func_get_args());
    }

    public function getQuantity(): int
    {
        return $this->items()->sum(function (Item $item) {
            return $item->getQuantity();
        });
    }
}
