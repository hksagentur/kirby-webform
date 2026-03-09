<?php

namespace Webform\Form\Collections;

use Webform\Form\Components\Component;
use Webform\Form\Components\Contracts\ProvidesChallenge;

/**
 * @template TKey of array-key
 * @template-covariant TValue of (Component&ProvidesChallenge)
 *
 * @extends Components<TKey, TValue>
 */
class Challenges extends Components
{
    public function valid(): static
    {
        return $this->filter(fn (Component&ProvidesChallenge $challenge) => $challenge->verify());
    }

    public function invalid(): static
    {
        return $this->reject(fn (Component&ProvidesChallenge $challenge) => $challenge->verify());
    }

    public function verifyAll(): bool
    {
        return ! $this->some(fn (Component&ProvidesChallenge $challenge) => ! $challenge->verify());
    }
}
