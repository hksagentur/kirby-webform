<?php

namespace Webform\Form\Components\Concerns;

use Webform\Form\Form;

trait HasErrors
{
    abstract public function getForm(): ?Form;
    abstract public function getName(): ?string;

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
        return ! empty($this->getErrors());
    }

    public function getErrors(): array
    {
        $name = $this->getName();

        if (! $name) {
            return [];
        }

        $messages = $this->getForm()?->getErrors();

        if (! $messages) {
            return [];
        }

        return $messages->get($name);
    }
}
