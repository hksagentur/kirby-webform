<?php

namespace Webform\Form\Collections;

use Webform\Form\Components\Component;
use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\Field;

/**
 * @template TKey of array-key
 * @template-covariant TValue of (Component&ProvidesChallenge)
 *
 * @extends Collection<TKey, TValue>
 */
class Challenges extends Collection
{
    /** @return string[] */
    public function fieldNames(): array
    {
        return array_filter($this->fields()->pluck('name'));
    }

    public function fields(): Fields
    {
        return $this->whereInstanceOf(Field::class)->pipeInto(Fields::class);
    }

    public function valid(): static
    {
        return $this->filter(fn (ProvidesChallenge $challenge) => $challenge->verify());
    }

    public function invalid(): static
    {
        return $this->reject(fn (ProvidesChallenge $challenge) => $challenge->verify());
    }

    public function verifyAll(): bool
    {
        return ! $this->some(fn (ProvidesChallenge $challenge) => ! $challenge->verify());
    }
}
