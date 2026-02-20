<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasLabel
{
    protected string|Closure|null $label = null;

    public function hasLabel(): bool
    {
        return $this->getLabel() !== null;
    }

    public function getLabel(): ?string
    {
        return $this->evaluate($this->label);
    }

    public function label(string|Closure|null $label): static
    {
        $this->label = $label;

        return $this;
    }
}
