<?php

namespace Vocalio\LaravelCart\Concerns;

use Vocalio\LaravelCart\Support\Helper;

trait CartCalculations
{
    public function getSubTotal(): Helper
    {
        return $this->items()->getSubTotal();
    }

    public function getSubTotalWithDiscount(): Helper
    {
        $totalItemsWithVat = $this->items()->getSubTotal()->withVat()->value();
        $totalItems = $this->items()->getSubTotal()->value();

        $totalModifiersWithVat = $this->modifiers()->discounts()->getTotal()->withVat()->value();
        $totalModifiers = $this->modifiers()->discounts()->getTotal()->value();

        return Helper::make()
            ->setValue($totalItems + $totalModifiers)
            ->setVatValue(($totalItemsWithVat - $totalItems) + ($totalModifiersWithVat - $totalModifiers));
    }

    public function getTotal(): Helper
    {
        $totalItemsWithVat = $this->items()->getTotal()->withVat()->value();
        $totalItems = $this->items()->getTotal()->value();

        $totalModifiersWithVat = $this->modifiers()->getTotal()->withVat()->value();
        $totalModifiers = $this->modifiers()->getTotal()->value();

        return Helper::make()
            ->setValue($totalItems + $totalModifiers)
            ->setVatValue($totalItemsWithVat + $totalModifiersWithVat - ($totalItems + $totalModifiers));
    }

    public function getQuantity(): int
    {
        return $this->items()->getQuantity();
    }
}
