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
        public int $vatRate = 0,
        public array $options = [],
        public bool $forceQuantity = false,
    ) {
        //
    }

    public function getUnitPrice(): Helper
    {
        return Helper::make()
            ->setValue($this->price)
            ->setVatRate($this->vatRate);
    }

    public function getTotalPrice(): Helper
    {
        $unitPrice = $this->getUnitPrice()->value();

        return Helper::make()
            ->setValue($unitPrice * $this->getQuantity())
            ->setVatRate($this->vatRate);
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
            vatRate: $json['vatRate'] ?? 0,
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
