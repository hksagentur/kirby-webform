<?php

namespace Webform\Form\Collections;

use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\Field;

/**
 * @template TKey of array-key
 * @template-covariant TValue of Field
 *
 * @extends Components<TKey, TValue>
 */
class Fields extends Components
{
    public function valid(): static
    {
        return $this->filter(fn (Field $field) => $field->isValid());
    }

    public function invalid(): static
    {
        return $this->filter(fn (Field $field) => $field->isInvalid());
    }

    public function required(): static
    {
        return $this->filter(fn (Field $field) => $field->isRequired());
    }

    public function disabled(): static
    {
        return $this->filter(fn (Field $field) => $field->isDisabled());
    }

    public function challenges(): Challenges
    {
        return $this->whereInstanceOf(ProvidesChallenge::class)->pipeInto(Challenges::class);
    }
}
