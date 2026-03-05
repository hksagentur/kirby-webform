<?php

namespace Webform\Form\Components\Concerns;

use Kirby\Toolkit\A;
use Webform\Form\Form;
use Webform\Toolkit\Flash;

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

        $data = Flash::get("webform.form.{$channel}.input");

        if (! $data) {
            return null;
        }

        return A::get($data, $key);
    }
}
