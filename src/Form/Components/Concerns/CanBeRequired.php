<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait CanBeRequired
{
    protected bool|Closure $isRequired = false;

    public function isRequired(): bool
    {
        return $this->evaluate($this->isRequired);
    }

    public function isOptional(): bool
    {
        return ! $this->isRequired();
    }

    public function required(bool|Closure $isRequired = true): static
    {
        $this->isRequired = $isRequired;

        return $this;
    }
}
