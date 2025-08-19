<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasGap
{
    protected string|Closure|null $gap = null;

    public static function create(): static
    {
        return new static();
    }

    public function hasGap(): bool
    {
        return $this->getGap() !== null;
    }

    public function getGap(): ?string
    {
        return $this->evaluate($this->gap);
    }

    public function gap(string|Closure|null $gap): static
    {
        $this->gap = $gap;

        return $this;
    }
}
