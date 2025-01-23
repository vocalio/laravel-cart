<?php

namespace Vocalio\LaravelCart\Data;

use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Vocalio\LaravelCart\Facades\LaravelCart;
use Vocalio\LaravelCart\Support\Helper;

class Item implements JsonSerializable
{
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

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        LaravelCart::persist();

        return $this;
    }

    public function model(): Model
    {
        $model = config('cart.item_model');

        return (new $model)->find($this->id);
    }

    public static function fromJson($json): self
    {
        return new Item(
            id: $json['id'],
            name: $json['name'],
            quantity: $json['quantity'],
            price: $json['price'] / 100,
            options: $json['options'] ?? [],
            forceQuantity: $json['forceQuantity'] ?? false,
        );
    }

    public function jsonSerialize(): mixed
    {
        $vars = get_object_vars($this);
        $vars['price'] = $this->price * 100;

        return $vars;
    }
}
