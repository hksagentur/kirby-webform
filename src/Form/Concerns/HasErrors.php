<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormStatus;
use Webform\Validation\Messages;

trait HasErrors
{
    protected ?Messages $errors = null;

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
        return $this->getErrors()->isNotEmpty();
    }

    public function getErrors(): Messages
    {
        return $this->errors ??= FormStatus::fromSession($this->getKey())?->getErrors() ?? new Messages();
    }
}
