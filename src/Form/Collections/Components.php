<?php

namespace Webform\Form\Collections;

use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\Component;
use Webform\Form\Components\Field;

/**
 * @template TKey of array-key
 * @template-covariant TValue of Component
 *
 * @extends Collection<TKey, TValue>
 */
class Components extends Collection
{
    public function fields(): Fields
    {
        return new Fields(
            $this->whereInstanceOf(Field::class)->all()
        );
    }

    public function challenges(): Challenges
    {
        return new Challenges(
            $this->whereInstanceOf(ProvidesChallenge::class)->all()
        );
    }
}
