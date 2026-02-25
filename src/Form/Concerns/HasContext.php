<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormContext;

trait HasContext
{
    protected ?FormContext $context = null;

    public function getFormContext(): FormContext
    {
        return $this->context ??= FormContext::fromSession($this->getKey());
    }
}
