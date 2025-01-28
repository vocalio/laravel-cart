<?php

namespace Vocalio\LaravelCart\Data;

use JsonSerializable;
use Vocalio\LaravelCart\Enums\ModifierType;
use Vocalio\LaravelCart\Support\Helper;

class Modifier implements JsonSerializable
{
    public function __construct(
        public mixed $id,
        public string $name,
        public int $quantity,
        public mixed $value,
        public int $vatRate = 0,
        public ModifierType $type = ModifierType::Discount,
        public array $options = [],
        public bool $forceQuantity = false,
    ) {
        //
    }

    public function value(): float
    {
        $value = floatval($this->value);

        if ($this->isPercentage()) {

            // Go throught all items cart and get discount value for each item
            return cart()->items()->sum(function (Item $item) use ($value) {
                return $item->getTotalPrice()->value() * ($value / 100);
            });
        } else {
            // When vatRate is set that means the value is with vat included
            if ($this->vatRate != 0) {
                return $value / (1 + ($this->vatRate / 100));
            }
        }

        return $value;
    }

    public function vatValue(): float
    {
        $value = floatval($this->value);

        if ($this->isPercentage()) {
            // Go throught all items cart and get discount value for each item
            $withVat = cart()->items()->sum(function (Item $item) use ($value) {
                return $item->getTotalPrice()->withVat()->value() * ($value / 100);
            });
        } else {
            $withVat = $value;
        }

        return $withVat - $this->value();
    }

    public function getUnitPrice(): Helper
    {
        return Helper::make()
            ->setValue($this->value())
            ->setVatValue($this->vatValue());
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

    public function model(): Model
    {
        /** @var Model $model */
        $model = config('cart.item_model');

        return (new $model)->find($this->id);
    }

    public function isPercentage(): bool
    {
        if (! is_string($this->value)) {
            return false;
        }

        return str($this->value)->endsWith('%');
    }

    public static function fromJson(array $json): self
    {
        return new Modifier(
            id: $json['id'],
            name: $json['name'],
            quantity: $json['quantity'],
            value: $json['value'],
            type: ModifierType::tryFrom($json['type']),
            vatRate: $json['vatRate'] ?? 0,
            options: $json['options'] ?? [],
            forceQuantity: $json['forceQuantity'] ?? false,
        );
    }

    public function jsonSerialize(): mixed
    {
        $vars = get_object_vars($this);
        $vars['value'] = $this->value;

        return $vars;
    }
}
