<?php

namespace Vocalio\LaravelCart\Support;

use Illuminate\Support\Number;

class Helper
{
    public ?float $value = null;
    public ?int $vatRate = null;
    public ?float $vatValue = null;
    public bool $withCurrency = false;
    public bool $withVat = false;

    public static function make(): static
    {
        return app(static::class);
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setVatRate(int $vatRate): self
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    public function setVatValue(float $vatValue): self
    {
        $this->vatValue = $vatValue;

        return $this;
    }

    public function withCurrency(bool $withCurrency = true): self
    {
        $this->withCurrency = $withCurrency;

        return $this;
    }

    public function withVat(bool $withVat = true): self
    {
        $this->withVat = $withVat;

        return $this;
    }

    public function withCurrencyAndVat(): self
    {
        $this->withVat = true;
        $this->withCurrency = true;

        return $this;
    }

    public function format(bool $withCurrency = false): float|string
    {
        $value = $this->value();

        if ($withCurrency) {
            $value = Number::currency($value);
        }

        return $value;
    }

    public function value(): float
    {
        $value = round($this->value, 2);

        if ($this->withVat && ($this->vatRate > 0 || $this->vatValue > 0)) {

            // If vatValue is set, use it, otherwise calculate it from vatRate
            if ($this->vatValue > 0) {
                $value = $value + $this->vatValue;
            } else {
                $value = $value * (1 + ($this->vatRate / 100));
            }
        }

        return $value;
    }

    public function __toString(): string
    {
        return (string) $this->format($this->withCurrency);
    }
}
