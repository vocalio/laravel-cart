<?php

namespace Vocalio\LaravelCart\Concerns;

use Vocalio\LaravelCart\Data\Item;
use Vocalio\LaravelCart\Support\Helper;

trait Cartable
{
    public function toCartItem(int $quantity = 1, array $options = [], bool $forceQuantity = false): Item
    {
        $price = $this->{$this->getCartItemPrice()};

        if ($price instanceof Helper) {
            $price = $price->value();
        }

        return new Item(
            id: $this->{$this->getCartItemKey()},
            name: $this->{$this->getCartItemName()},
            quantity: $quantity,
            price: $price,
            vatRate: $this->{$this->getCartItemVatRate()} ?? 0,
            options: $options,
            forceQuantity: $forceQuantity,
        );
    }

    public function addToCart(int $quantity = 1, array $options = [], bool $forceQuantity = false): self
    {
        cart()->add($this->toCartItem(...get_defined_vars()));

        return $this;
    }

    protected function getCartItemKey(): string
    {
        return 'id';
    }

    protected function getCartItemName(): string
    {
        return 'title';
    }

    protected function getCartItemPrice(): string
    {
        return 'price';
    }

    protected function getCartItemVatRate(): string
    {
        return 'vat_rate';
    }
}
