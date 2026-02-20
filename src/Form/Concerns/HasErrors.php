<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormContext;
use Webform\Form\MessageBag;

trait HasErrors
{
    abstract public function getContext(): FormContext;

    public function isValid(): bool
    {
        return ! $this->hasErrors();
    }

    public function isInvalid(): bool
    {
        return $this->hasErrors();
    }

    public function hasErrors(): bool
    {
        return $this->getContext()->hasErrors();
    }

    public function getErrors(): MessageBag
    {
        return $this->getContext()->getErrors();
    }
}
