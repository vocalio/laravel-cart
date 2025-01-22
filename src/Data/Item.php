<?php

namespace Vocalio\LaravelCart\Data;

use Vocalio\LaravelCart\Support\Helper;

class Item {
    public function __construct(
        public mixed $id,
        public string $name,
        public int $quantity,
        public float $price,
        public array $options = [],
        public bool $forceQuantity = false,
    ) {
        //
    }

    public function getUnitPrice(bool $withCurrency = false): float|string
    {
        return Helper::format($this->price, ...func_get_args());
    }

    public function getTotalPrice(bool $withCurrency = false): float|string
    {
        /** @var float $unitPrice */
        $unitPrice = $this->getUnitPrice();

        return Helper::format($unitPrice * $this->getQuantity(), ...func_get_args());
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
