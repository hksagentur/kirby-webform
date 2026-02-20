<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Toolkit\Str;
use Webform\Form\Form;
use Webform\Support\Flash;

trait CanBeObfuscated
{
    protected bool|Closure $obfuscate = false;

    abstract public function getForm(): ?Form;

    public function getName(): ?string
    {
        $name = parent::getName();

        if (! $name || ! $this->shouldObfuscate()) {
            return $name;
        }

        $form = $this->getForm()?->getKey();

        if (! $form) {
            return $name;
        }

        return Flash::getOrPut(
            key: "webform.form.{$form}.field.{$name}.obfuscatedName",
            value: fn () => $name . '_' . Str::random(8),
        );
    }

    public function shouldObfuscate(): bool
    {
        return $this->evaluate($this->obfuscate);
    }

    public function obfuscate(bool|Closure $obfuscate = true): static
    {
        $this->obfuscate = $obfuscate;

        return $this;
    }
}
