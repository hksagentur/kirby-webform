<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormContext;
use Webform\Validation\Message;

trait HasStatus
{
    abstract public function getFormContext(): FormContext;

    public function getStatus(): ?Message
    {
        return $this->getFormContext()->getStatus();
    }
}
