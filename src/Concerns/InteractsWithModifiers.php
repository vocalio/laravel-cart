<?php

namespace Vocalio\LaravelCart\Concerns;

use Vocalio\LaravelCart\Data\Modifier;
use Vocalio\LaravelCart\Events\CartModifierAdded;
use Vocalio\LaravelCart\Events\CartModifierRemoved;
use Vocalio\LaravelCart\Events\CartModifierUpdated;
use Vocalio\LaravelCart\ModifierCollection;

trait InteractsWithModifiers
{
    /**
     * @return \Vocalio\LaravelCart\ModifierCollection<mixed, Modifier>
     */
    public function modifiers(): ModifierCollection
    {
        return $this->modifiers;
    }

    public function addModifier(Modifier $modifier): self
    {
        // If the item is already in the cart we will just update it
        if ($this->modifiers->contains('id', $modifier->id)) {
            $this->updateModifier($modifier->id, $modifier);
        } else {
            $this->modifiers->add($modifier);

            $this->persist();

            event(new CartModifierAdded($this->record, $modifier));
        }

        return $this;
    }

    public function updateModifier(mixed $id, Modifier $modifier): self
    {
        $this->modifiers = $this->modifiers()->map(function (Modifier $cartModifier) use ($id, $modifier) {
            if ($cartModifier->id === $id) {
                foreach (get_object_vars($modifier) as $property => $value) {
                    if ($property == 'quantity') {
                        // If the item is forced to have a specific quantity we will just set it
                        if ($modifier->forceQuantity) {
                            $cartModifier->quantity = $value;
                        } else {
                            $cartModifier->quantity += $value;
                        }
                    } else {
                        $cartModifier->{$property} = $value;
                    }
                }
            }

            return $cartModifier;
        });

        $this->persist();

        event(new CartModifierUpdated($this->record, $modifier));

        return $this;
    }

    public function removeModifier(mixed $id): self
    {
        $this->modifiers = $this->modifiers()->reject(function (Modifier $modifier) use ($id) {
            return $modifier->id === $id;
        });

        $this->persist();

        event(new CartModifierRemoved($this->record, $id));

        return $this;
    }
}
