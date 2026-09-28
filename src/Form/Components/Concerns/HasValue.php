<?php

namespace Webform\Form\Components\Concerns;

use Webform\Form\Form;
use Webform\Form\FormInput;

trait HasValue
{
    protected mixed $defaultValue = null;

    abstract public function getForm(): ?Form;

    public function getDefaultValue(): mixed
    {
        return $this->evaluate($this->defaultValue);
    }

    public function default(mixed $value): static
    {
        $this->defaultValue = $value;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->getOldValue() ?? $this->getDefaultValue();
    }

    public function getOldValue(): mixed
    {
        $key = $this->getName();

        if (! $key) {
            return null;
        }

        $channel = $this->getForm()?->getKey();

        if (! $channel) {
            return null;
        }

        return FormInput::fromSession($channel)?->get($key);
    }
}
