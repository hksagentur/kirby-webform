<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasHint
{
    protected string|Closure|null $hint = null;

    public function hasHint(): bool
    {
        return $this->getHint() !== null;
    }

    public function getHint(): ?string
    {
        return $this->evaluate($this->hint);
    }

    public function hint(string|Closure|null $text): static
    {
        $this->hint = $text;

        return $this;
    }
}
