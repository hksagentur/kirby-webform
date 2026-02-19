<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormState;

trait HasState
{
    protected ?FormState $state = null;

    abstract public function getKey(): string;

    public function getState(): FormState
    {
        return $this->state ??= new FormState($this);
    }
}
