<?php

namespace Vocalio\LaravelCart;

use Illuminate\Support\Collection;
use Vocalio\LaravelCart\Data\Modifier;
use Vocalio\LaravelCart\Enums\ModifierType;
use Vocalio\LaravelCart\Support\Helper;

class ModifierCollection extends Collection
{
    public function parse(array $items = []): self
    {
        $this->items = collect($items)->map(function ($item) {
            if ($item instanceof Modifier) {
                return $item;
            }

            try {
                return Modifier::fromJson($item);
            } catch (\Exception $e) {
                //
            }
        })
            ->filter()
            ->toArray();

        return $this;
    }

    public function getTotal(): Helper
    {
        $sumWithVat = $this->sum(function (Modifier $modifier) {
            return $modifier->getTotalPrice()->withVat()->value();
        });

        $sum = $this->sum(function (Modifier $modifier) {
            return $modifier->getTotalPrice()->value();
        });

        return Helper::make()
            ->setValue($sum)
            ->setVatValue($sumWithVat - $sum);
    }

    public function discounts(): self
    {
        return $this->whereType(ModifierType::Discount);
    }

    public function taxes(): self
    {
        return $this->whereType(ModifierType::Tax);
    }

    public function shippings(): self
    {
        return $this->whereType(ModifierType::Shipping);
    }

    public function extras(): self
    {
        return $this->whereType(ModifierType::Extra);
    }

    public function whereType(ModifierType $modifierType): self
    {
        return $this->filter(fn (Modifier $item) => $item->type === $modifierType);
    }

    public function find(mixed $id): ?Modifier
    {
        return $this->first(fn (Modifier $item) => $item->id == $id);
    }
}
