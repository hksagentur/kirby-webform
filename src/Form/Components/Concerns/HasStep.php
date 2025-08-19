<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasStep
{
    protected int|float|string|Closure|null $step = null;

    public function getStep(): int|float|string|null
    {
        return $this->evaluate($this->step);
    }

    public function step(int|float|string|Closure|null $interval): static
    {
        $this->step = $interval;

        return $this;
    }
}
