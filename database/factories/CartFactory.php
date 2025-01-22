<?php

namespace Vocalio\LaravelCart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vocalio\LaravelCart\Models\Cart;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'data' => [],
        ];
    }
}
