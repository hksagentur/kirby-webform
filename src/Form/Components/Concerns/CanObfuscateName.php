<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Toolkit\Str;
use Webform\Session\TransientData;

trait CanObfuscateName
{
    protected bool|Closure $obfuscateName = false;

    public function getName(): ?string
    {
        $name = parent::getName();

        if (! $name || ! $this->shouldObfuscateName()) {
            return $name;
        }

        return TransientData::instance()->getOrPut(
            key: "webform.field.{$name}.name",
            value: fn () => $name . '_' . Str::random(length: 8, type: 'alphalower'),
        );
    }

    public function shouldObfuscateName(): bool
    {
        return $this->evaluate($this->obfuscateName);
    }

    public function obfuscateName(bool|Closure $obfuscate = true): static
    {
        $this->obfuscateName = $obfuscate;

        return $this;
    }
}
