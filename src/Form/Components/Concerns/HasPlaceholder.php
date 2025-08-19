<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasPlaceholder
{
    protected string|Closure|null $placeholder = null;

    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder);
    }

    public function placeholder(string|Closure|null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }
}
