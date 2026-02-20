<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormContext;
use Webform\Form\StatusMessage;

trait HasStatus
{
    abstract public function getContext(): FormContext;

    public function getStatus(): ?StatusMessage
    {
        return $this->getContext()->getStatus();
    }
}
