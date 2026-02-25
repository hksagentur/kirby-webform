<?php

namespace Webform\Form\Collections;

use Webform\Form\Components\Field;
use Webform\Validation\Messages;

/**
 * @template TKey of array-key
 * @template-covariant TValue of Field
 *
 * @extends Components<TKey, TValue>
 */
class Fields extends Components
{
    /** @return string[] */
    public function fieldNames(): array
    {
        return array_filter($this->pluck('name'));
    }

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

    public function errors(): Messages
    {
        $errors = [];

        foreach ($this->components as $field) {
            $errors[$field->getName()] = $field->getErrors();
        }

        return new Messages($errors);
    }
}
