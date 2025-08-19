<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait CanBeLengthConstrained
{
    protected int|Closure|null $length = null;
    protected int|Closure|null $minLength = null;
    protected int|Closure|null $maxLength = null;

    public function getLength(): ?int
    {
        return $this->evaluate($this->minLength);
    }

    public function getMinLength(): ?int
    {
        return $this->evaluate($this->minLength);
    }

    public function getMaxLength(): ?int
    {
        return $this->evaluate($this->maxLength);
    }

    public function length(int|Closure|null $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function minLength(int|Closure|null $length): static
    {
        $this->minLength = $length;

        return $this;
    }

    public function maxLength(int|Closure|null $length): static
    {
        $this->maxLength = $length;

        return $this;
    }
}
