<?php

namespace Vocalio\LaravelCart\Concerns;

use Vocalio\LaravelCart\Data\Item;
use Vocalio\LaravelCart\Support\Helper;

trait CartCalculations
{
    public function getTotal(): Helper
    {
        $sumWithVat = $this->items()->sum(function (Item $item) {
            return $item->getTotalPrice()->withVat()->value();
        });

        $sum = $this->items()->sum(function (Item $item) {
            return $item->getTotalPrice()->value();
        });

        return Helper::make()
            ->setValue($sum)
            ->setVatValue($sumWithVat - $sum);
    }

    public function getQuantity(): int
    {
        return $this->items()->sum(function (Item $item) {
            return $item->getQuantity();
        });
    }
}
