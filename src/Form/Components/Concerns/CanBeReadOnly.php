<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait CanBeReadOnly
{
    protected bool|Closure $isReadOnly = false;

    public function isReadOnly(): bool
    {
        return $this->evaluate($this->isReadOnly);
    }

    public function readOnly(bool|Closure $isReadOnly = true): static
    {
        $this->isReadOnly = $isReadOnly;

        return $this;
    }
}
