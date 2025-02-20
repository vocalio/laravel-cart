<?php

namespace Vocalio\LaravelCart\Data;

use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
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
        return $this->getUnitPrice()
            ->multiply($this->getQuantity());
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        cart()->persist();

        return $this;
    }

    public function getVatRate(): int
    {
        return $this->vatRate;
    }

    public function setVatRate(int $vatRate): self
    {
        $this->vatRate = $vatRate;
        cart()->persist();

        return $this;
    }

    public function getTotalVatValue(): float
    {
        return $this->getTotalPrice()->withVat()->value() - $this->getTotalPrice()->value();
    }

    public function model(): Model
    {
        /** @var Model $model */
        $model = config('cart.item_model');

        return (new $model)->find($this->id);
    }

    public static function fromJson(array $json): self
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
