<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormContext;
use Webform\Validation\Messages;

trait HasErrors
{
    abstract public function getFormContext(): FormContext;

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
        return $this->getFormContext()->hasErrors();
    }

    public function getErrors(): Messages
    {
        return $this->getFormContext()->getErrors();
    }
}
