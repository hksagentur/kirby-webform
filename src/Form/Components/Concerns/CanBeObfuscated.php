<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Toolkit\Str;
use Webform\Session\TransientData;

trait CanBeObfuscated
{
    protected bool|Closure $obfuscate = false;

    public function getName(): ?string
    {
        $name = parent::getName();

        if (! $name || ! $this->shouldObfuscate()) {
            return $name;
        }

        return TransientData::instance()->getOrPut(
            key: "webform.field.{$name}.name",
            value: fn () => $name . '_' . Str::random(length: 8, type: 'alphalower'),
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
