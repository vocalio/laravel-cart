<?php

namespace Vocalio\LaravelCart\Concerns;

use Vocalio\LaravelCart\Events\CartSaved;
use Vocalio\LaravelCart\ItemsCollection;
use Vocalio\LaravelCart\Models\Cart;

trait InteractsWithCart
{
    public function sessionKey(): ?string
    {
        return session()->get(config('cart.session_name'), null);
    }

    public function model(): ?Cart
    {
        $model = config('cart.model');

        return new $model;
    }

    public function cart(): self
    {
        $cart = $this->model()->query();

        if (($userModel = config('cart.user_model')) && auth()->check()) {
            $userModel = new $userModel;
            $cart->where($userModel->getForeignKey(), auth()->id())
                ->latest();
        } else {
            $cart->whereKey($this->sessionKey());
        }

        if ($cart = $cart->first()) {
            $this->record = $cart;
            $this->items = $cart->data;
        } else {
            $this->record = $this->model(); // Make a fake cart instance
            $this->items = new ItemsCollection;
        }

        return $this;
    }

    public function persist(): self
    {
        $values = [
            'data' => $this->items,
        ];

        // Add the user_id to the values
        if (($userModel = config('cart.user_model')) && auth()->check()) {
            $userModel = new $userModel;
            $values[$userModel->getForeignKey()] = auth()->id();
        }

        // Create or update the cart in the database
        $cart = $this->model()->updateOrCreate(['id' => $this->sessionKey()], $values);

        // If the record is not set, set it to the cart
        if (! $this->record) {
            $this->record = $cart;
        }

        // Store the cart key in the session
        session()->put(config('cart.session_name'), $cart->getKey());

        // Fire the CartSaved event
        event(new CartSaved($this->record));

        return $this;
    }

    public function destroy(): self
    {
        session()->forget(config('cart.session_name'));

        event(new CartDestroyed($this->record));

        return $this;
    }
}
