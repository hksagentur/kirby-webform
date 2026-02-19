<?php

namespace Webform\Form\Components\Concerns;

use Kirby\Cms\S;
use Kirby\Toolkit\A;
use Webform\Form\Form;

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

        $form = $this->getForm()?->getKey();

        if (! $form) {
            return null;
        }

        $data = S::get("webform.form.{$form}.input");

        if (! $data) {
            return null;
        }

        return A::get($data, $key);
    }
}
